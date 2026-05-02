# Cairns Stingers — Project Notes

> Running sheet for Claude Code. Future sessions read this first. **Update this file at the end of every session.**

## Project

Cairns Stingers AFL Masters club website. Far North Queensland.

## Stack

- WordPress + **ACF Pro** (theme `style.css` declares Pro as required, not just regular ACF)
- Hosted on SiteGround
- Domain: `cairnsstingers.com.au`
- Active theme folder name on server: **`cairns-stingers 11`** — note the trailing space and number, not bare `cairns-stingers`. Theme's canonical name (from `style.css`) is "Cairns Stingers", text domain `stingers`, version 1.0.
- Note: there are 12+ theme folders on the server (`cairns-stingers`, `cairns-stingers 3` through `12`, plus default WP themes). `11` is the active. **`cairns-stingers 12` already exists** but is older than 11 — it's an earlier experiment, not a successor. Don't reuse that name when cloning.

## Project layout

This project lives at `~/Dropbox/StingersSite/`.

| Path | Purpose |
| --- | --- |
| `working/` | Live in-progress HTML (`v11-work.html`), companion asset folder, scrubbed local copy of `_deploy.php`. `working/archive/` keeps recent HTML snapshots for diffing. |
| `theme/` | WordPress theme files. `theme/live-current/` holds a pull of `cairns-stingers 11` from production (3.9 MB, 27 files, pulled 2026-05-02 — see Sec. layout below). |
| `notes/` | Running sheet (`stingers-running-sheet.md`), Style Guide, reference docs. |
| `.env` | Gitignored. Holds `X_AUTH_SECRET` for the deploy endpoint. |
| `CLAUDE.md` | This file. |

## Current state

- Project initialised as a git repo on `main` branch (2026-05-02). No commits yet.
- Standalone HTML lives at `https://cairnsstingers.com.au/sting/index.html` — a static preview served from the `/sting/` folder, **separate from WordPress**. Used for testing only — the eventual home is the actual root domain on WordPress.
- **Pending: 5 About-section mobile patches** to `working/v11-work.html`, then port to WordPress as `front-page.php`.
- Live theme at `theme/live-current/`: top-level templates are `front-page.php` (12 KB), `header.php`, `footer.php`, `functions.php` (26 KB), and page templates `page-club-song`, `page-gallery`, `page-games`, `page-players` (20 KB), `page-sponsors`. Subdirs: `assets/{css,images,js}`, `inc/` (demo-content, design-controls, player-frontend, sponsor-enquiries), `template-parts/player-card.php`. Asset images include `logo.png`, `logo-alt.png`, `logo.webp`, `player-action.png`, `player-dark.webp`, `player-portrait.webp`.
- **Migration approach (agreed 2026-05-02):**
  - Clone current theme `cairns-stingers 11` → new theme name (e.g. `cairns-stingers-12`). Old stays installed-but-inactive as instant rollback.
  - Inventory active plugins + dynamic features on current site BEFORE rebuilding pages, so we don't silently drop features.
  - Build shared components first (`header.php`, `footer.php`, nav, CSS tokens, asset bundle), then per-page port: home → about → fixtures → ...
  - Page-by-page, content/functionality similar to current, design from v11 HTML.

## SFTP / hosting

- Host: `ssh.cairnsstingers.com.au`
- Port: `18765`
- User: `u264-nwje2medwyga`
- Auth: SSH key at `~/.ssh/cairns_stingers_ed25519` (private key NEVER opened in chat).
- Convenience: SSH config alias `stingers` is set up — `sftp stingers` works from any terminal.
- Server layout: docroot is `~/www/cairnsstingers.com.au/public_html/`. WordPress is in there. Static preview at `public_html/sting/`. Active theme at `public_html/wp-content/themes/cairns-stingers 11/`.

## Deploy mechanism

`POST https://cairnsstingers.com.au/sting/_deploy.php`

- Header `X-Auth:` — value from `.env` (`X_AUTH_SECRET`)
- Header `X-Target:` — destination filename, **flat into `/sting/` only** (no subpaths, no `/` or `\`, regex `^[A-Za-z0-9._-]+$`)
- Body: raw file content (binary safe, streamed)
- Health check: `GET` with same `X-Auth` header → returns `{ok, health, allowed_exts, max_bytes, time}`.
- Allowed extensions whitelist (no PHP uploads): `html htm css js mjs json xml txt map svg jpg jpeg png gif webp avif ico mp4 webm mov m4v mp3 wav ogg woff woff2 ttf otf`.
- Max body 128 MB.
- The actual server-side script enforces the secret. Local copy at `working/_deploy.php` has the secret replaced with `__REPLACE_ON_SERVER__` placeholder.

## Workflow rules

- **Edit in place.** No `v12-work.html`, `v13-work.html`, etc. Keep one live HTML file. Use git for history (once initialised).
- **Grep before patching.** Before modifying any selector / function / string, search across the project to find every place it appears.
- **Update this file at the end of every session.** Record current state, blockers, what's next.

## Design tokens

- `--or` orange: `#F47920`
- `--ord` deep orange: `#FF5A1F`
- Navy background: `#0A1628`
- Body font: **Plus Jakarta Sans**
- Mono font: **JetBrains Mono**

## Inclusivity language rule

The Stingers welcomes everyone — including women in AFL Masters. Avoid:

- ❌ "blokes", "brotherhood", "lads"

Use instead:

- ✅ "locals", "mateship", "men and women", "everyone"

## Security / exposure log

- **2026-05-02** — previous SSH key burned via chat-log exposure. Rotated to fresh `ed25519` keypair (`~/.ssh/cairns_stingers_ed25519`).
- **2026-05-02** — previous X-Auth deploy secret (`dc5b4f5f...`) burned via chat-log exposure. Rotated. Old secret now rejected by server (verified HTTP 401). New secret only lives in `~/Dropbox/StingersSite/.env`.

**Rule:** never `cat`/`Read` `_deploy.php` from the live server in chat. The local `working/_deploy.php` is safe — secret is `__REPLACE_ON_SERVER__`.
