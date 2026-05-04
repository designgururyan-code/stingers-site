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

- Project initialised as a git repo on `main` branch (2026-05-02). One commit so far (initial baseline).
- Standalone HTML lives at `https://cairnsstingers.com.au/sting/index.html` — a static preview served from the `/sting/` folder, **separate from WordPress**. Used for testing only — the eventual home is the actual root domain on WordPress.
- **9 mobile patches landed in `working/v11-work.html`** and deployed to `/sting/index.html` on **2026-05-02 12:49 AEST** (UTC 02:49:35). Re-deployed at **13:04 AEST** (UTC 03:04:38) with two follow-up fixes: (a) `.about__body` mobile override (`font-size:15px; max-width:none` at ≤680px) to stop body text overflowing the frame on phones; (b) `<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">` etc. in `<head>` as belt-and-braces against aggressive caching. Patches: (1) bring-card num align-self:start; (2) About mobile top-padding reduced; (3) About photo clip-path mask reveal + ±24px parallax; (4) moment-card hover/active uses `--ord` + mobile IO active-state class; (5) about__cards converted to scroll-snap horizontal slider on ≤560px with vanilla clone-edges infinite loop; (6) hero `.recruit__title` `<br>` drop on mobile — already pre-applied (no-op); (7) recruit mobile padding-top tightened to `clamp(40,8vw,80)`; (8) eyebrow row dot alignment — target markup absent (no-op); (9) moment caption text `Watson's Oval #2 ● Wed 5pm`. Verification: HTTP 200 from deploy endpoint, sha256 matched, "Wed 5pm" marker present on live.
- **Asset references rewritten** in `v11-work.html` to match live convention: CDN URLs for fonts (Google Fonts), Lenis (`unpkg`), Swiper (`jsdelivr`); flat filenames (`stingers-logo-transparent.png`, `stingers-moment.jpg`) for images already in `/sting/` root; `STINGERSLOGO.-2png.webp` references the WP media URL. Then a second pass converted ALL remaining flat asset paths (videos + posters + the two flat images) to absolute URLs back to `https://cairnsstingers.com.au/sting/{file}` so the HTML is fully portable — works from any host.
- **Mobile slider follow-up fixes (post-deploy iteration on the tunnel preview):** the original 78%-width-with-peek slider design was reversed at the user's request to **full-width cards** (`flex:0 0 100%`). To keep the swipe affordance, an animated "Swipe →" hint was added below the slider (mobile-only, mono caption + dashes pattern matching the existing design). To fix iOS Safari swiping both axes, added `touch-action:pan-x`, `overscroll-behavior-x:contain`, `overflow-y:hidden` to the slider. To fix cards flickering off as they scroll past the viewport edge, branched the existing `inView` reveal handler so on mobile the cards just get `opacity:1` (no entrance/exit animation that would re-fire as the slider is swiped). Photo aspect ratio dropped from 4/5 to 4/3 on mobile so cards aren't a screen-and-a-half tall. Loop logic now toggles `scroll-snap-type:none` during the instant scrollLeft jump so iOS doesn't ping-pong against snap.
- **About-section text overflow fix:** the canonical fix was `min-width:0` on `.about__copy` and `.about__split` (grid/flex children default to content-sized min-width, which causes text to push past the parent). Plus `.about{overflow-x:hidden}` as a defensive clamp, `overflow-wrap:break-word` on title and body, and tighter title clamp on mobile.
- **Re-deploy 2026-05-04 (root-cause fix for mobile overflow chain):** the slider was widening the entire page beyond 100vw because no ancestor was clipping horizontal overflow. Cascade of fixes:
  - `html,body{ overflow-x:clip; max-width:100vw }` — definitive horizontal clamp at the document root.
  - `.about__cards` on mobile gets `max-width:100vw; width:100vw; margin-left/right:calc(-1 * clamp(24px,4vw,72px))` — slider goes edge-to-edge on mobile by extending OUT of `.about__inner`'s padding (instead of pushing parent wider).
  - Cards switched to `flex:0 0 100vw; width:100vw` so each card is exactly one viewport (was `flex:0 0 100%` which inherited from now-100vw parent — cleaner to be explicit).
  - `.moment-card__photo` mobile: `aspect-ratio:4/5` (back to portrait), `max-height:60vh`, `overflow:hidden`. Image inside also capped at `max-height:60vh` with `object-fit:cover`. Prevents single card dominating the viewport while keeping portrait-style framing.
  - Hero `.card__wordmark` font shrunk from `clamp(56,18vw,96)` to `clamp(44,13vw,88)` (uppercase "THE WORK" was overrunning card padding).
  - Hero `.card__tagline` refactored from `display:inline-flex` to standard block flow with absolutely-positioned dot pseudo-element — anonymous text inside flex containers wasn't wrapping reliably.
- **Cascade-order bug discovered 2026-05-04:** earlier `@media` overrides for `.about__body`, `.about__title`, etc. were placed *above* their base rules in the file, so the base rules (later in cascade, same specificity) silently overrode the overrides. Lesson: when adding a mobile override, place it AFTER the base rule the override targets, OR use a higher-specificity selector. The about-section overrides have now been moved to follow the relevant base rules.
- **Next**: port `v11-work.html` design into a cloned WP theme (`cairns-stingers-NN`, not `12` — that name is taken by an older experiment) as `front-page.php` + section partials. Inventory active plugins / ACF field groups before starting.
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

## Caching gotcha

`/sting/.htaccess` is correctly configured (`Cache-Control "public, max-age=0, must-revalidate"` for `.html`), but **SiteGround's NGINX Direct Delivery / Dynamic Cache layer overrides it** — confirmed via `x-proxy-cache-info: DT:1` header on responses, and the live response sending `cache-control: max-age=15552000` despite the `.htaccess`. NGINX serves static HTML directly without going through Apache, so `.htaccess` `Header` directives don't reach the response.

Implications:
- After every deploy, the cached HTML lingers up to 180 days from a browser's perspective.
- **Manual fix per deploy**: SiteGround Site Tools → Speed → Caching → Dynamic Cache → kebab → Flush Cache.
- **Permanent fix (recommended)**: in Site Tools, add `/sting/*` (or the full URL) as a Dynamic Cache exclusion. After that, `/sting/*` always hits Apache, `.htaccess` rules apply, and deploys are instantly visible.
- Until the exclusion is set up, append a unique query string to test fresh deploys: `?cb=YYYYMMDD-HHMM`.
