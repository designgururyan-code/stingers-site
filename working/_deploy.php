<?php
/**
 * Cairns Stingers deploy endpoint
 *
 * Drop-in replacement for /sting/_deploy.php — same API contract as the
 * original, with extended whitelist so video/image/font assets can be
 * pushed straight from chat instead of cPanel uploads.
 *
 * API:
 *   GET  /sting/_deploy.php          → 200 JSON status (requires X-Auth)
 *   POST /sting/_deploy.php          → 200 JSON {ok, target, bytes, sha256, time}
 *     headers: X-Auth, X-Target (relative filename, e.g. "index.html")
 *     body:    raw file content (Content-Type ignored; binary safe)
 *
 * Errors return JSON with "ok": false and an "error" code.
 *
 * SETUP:
 *   1. Paste the existing SHARED_SECRET value below (same one Ryan's been using).
 *   2. Upload to /sting/_deploy.php overwriting the current file.
 *   3. If you hit "payload_too_large" or empty-body errors on big files,
 *      drop a .htaccess into /sting/ with:
 *          php_value post_max_size 128M
 *          php_value upload_max_filesize 128M
 *          php_value memory_limit 256M
 *          php_value max_execution_time 120
 *      (SiteGround supports php_value in .htaccess on most plans.)
 *
 * Requires PHP 8.0+.
 */
 
declare(strict_types=1);
 
// ─────────────────────────────────────────────────────────────────────────────
// Config
// ─────────────────────────────────────────────────────────────────────────────
 
const SHARED_SECRET = '__REPLACE_ON_SERVER__';
 
const TARGET_DIR = __DIR__;
const LOG_FILE   = __DIR__ . '/_deploy.log';
 
// 128 MB ceiling — well clear of typical mobile mp4 size.
const MAX_BYTES = 128 * 1024 * 1024;
 
// Whitelist by extension. PHP / executable types are deliberately absent.
const ALLOWED_EXTS = [
    // Markup / scripts / styles
    'html', 'htm', 'css', 'js', 'mjs', 'json', 'xml', 'txt', 'map',
    // Images
    'svg', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'ico',
    // Video / audio
    'mp4', 'webm', 'mov', 'm4v', 'mp3', 'wav', 'ogg',
    // Fonts
    'woff', 'woff2', 'ttf', 'otf',
];
 
// ─────────────────────────────────────────────────────────────────────────────
// Helpers
// ─────────────────────────────────────────────────────────────────────────────
 
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
 
function respond(int $code, array $data): never
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}
 
function audit(string $line): void
{
    @file_put_contents(
        LOG_FILE,
        '[' . date('c') . '] ' . $line . "\n",
        FILE_APPEND | LOCK_EX
    );
}
 
function clientIp(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? '?';
}
 
// ─────────────────────────────────────────────────────────────────────────────
// Auth (timing-safe)
// ─────────────────────────────────────────────────────────────────────────────
 
$auth = $_SERVER['HTTP_X_AUTH'] ?? '';
$authOk = is_string($auth)
    && $auth !== ''
    && SHARED_SECRET !== 'PASTE_SECRET_HERE'
    && hash_equals(SHARED_SECRET, $auth);
 
// ─────────────────────────────────────────────────────────────────────────────
// Routing
// ─────────────────────────────────────────────────────────────────────────────
 
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
 
if ($method === 'GET') {
    if (!$authOk) {
        respond(401, ['ok' => false, 'error' => 'unauthorized']);
    }
    respond(200, [
        'ok'           => true,
        'health'       => 'ready',
        'allowed_exts' => ALLOWED_EXTS,
        'max_bytes'    => MAX_BYTES,
        'time'         => date('c'),
    ]);
}
 
if ($method !== 'POST') {
    respond(405, ['ok' => false, 'error' => 'method_not_allowed']);
}
 
if (!$authOk) {
    audit('AUTH_FAIL ip=' . clientIp());
    respond(401, ['ok' => false, 'error' => 'unauthorized']);
}
 
// ─────────────────────────────────────────────────────────────────────────────
// Target validation
// ─────────────────────────────────────────────────────────────────────────────
 
$target = $_SERVER['HTTP_X_TARGET'] ?? '';
 
if (!is_string($target) || $target === '' || strlen($target) > 200) {
    respond(400, ['ok' => false, 'error' => 'missing_or_invalid_target']);
}
 
// Reject path traversal, leading slashes, NUL bytes, subpaths
if (
    str_contains($target, '..')
    || str_contains($target, "\0")
    || str_starts_with($target, '/')
    || str_starts_with($target, '\\')
    || str_contains($target, '/')
    || str_contains($target, '\\')
) {
    audit("PATH_REJECT target=" . substr($target, 0, 100) . " ip=" . clientIp());
    respond(400, ['ok' => false, 'error' => 'invalid_target_path']);
}
 
// Filename must match a sane pattern
if (!preg_match('/^[A-Za-z0-9._-]+$/', $target)) {
    respond(400, ['ok' => false, 'error' => 'invalid_filename_chars']);
}
 
// Extension check
$ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));
if ($ext === '' || !in_array($ext, ALLOWED_EXTS, true)) {
    audit("EXT_REJECT target=$target ext=$ext ip=" . clientIp());
    respond(400, [
        'ok'           => false,
        'error'        => 'extension_not_allowed',
        'ext'          => $ext,
        'allowed_exts' => ALLOWED_EXTS,
    ]);
}
 
// ─────────────────────────────────────────────────────────────────────────────
// Stream body to a temp file, hashing as we go
// ─────────────────────────────────────────────────────────────────────────────
 
$tmp = tempnam(sys_get_temp_dir(), 'sting_deploy_');
if ($tmp === false) {
    respond(500, ['ok' => false, 'error' => 'tmp_create_failed']);
}
 
$in  = fopen('php://input', 'rb');
$out = fopen($tmp, 'wb');
 
if (!$in || !$out) {
    @unlink($tmp);
    respond(500, ['ok' => false, 'error' => 'stream_open_failed']);
}
 
$ctx     = hash_init('sha256');
$bytes   = 0;
$tooBig  = false;
 
while (!feof($in)) {
    $chunk = fread($in, 65536); // 64 KB
    if ($chunk === '' || $chunk === false) {
        break;
    }
    $bytes += strlen($chunk);
    if ($bytes > MAX_BYTES) {
        $tooBig = true;
        break;
    }
    hash_update($ctx, $chunk);
    fwrite($out, $chunk);
}
 
fclose($in);
fclose($out);
 
if ($tooBig) {
    @unlink($tmp);
    respond(413, [
        'ok'        => false,
        'error'     => 'payload_too_large',
        'max_bytes' => MAX_BYTES,
    ]);
}
 
if ($bytes === 0) {
    @unlink($tmp);
    respond(400, ['ok' => false, 'error' => 'empty_body']);
}
 
$sha  = hash_final($ctx);
$dest = TARGET_DIR . '/' . $target;
 
// ─────────────────────────────────────────────────────────────────────────────
// Atomic install
// ─────────────────────────────────────────────────────────────────────────────
 
if (!@rename($tmp, $dest)) {
    // Cross-device fallback
    if (!@copy($tmp, $dest)) {
        @unlink($tmp);
        audit("WRITE_FAIL target=$target ip=" . clientIp());
        respond(500, ['ok' => false, 'error' => 'write_failed']);
    }
    @unlink($tmp);
}
 
@chmod($dest, 0644);
 
audit(sprintf(
    'OK target=%s bytes=%d sha=%s ip=%s ua=%s',
    $target,
    $bytes,
    $sha,
    clientIp(),
    substr($_SERVER['HTTP_USER_AGENT'] ?? '?', 0, 80)
));
 
respond(200, [
    'ok'     => true,
    'target' => $target,
    'bytes'  => $bytes,
    'sha256' => $sha,
    'time'   => date('c'),
]);