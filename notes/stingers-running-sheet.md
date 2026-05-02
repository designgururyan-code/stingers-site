# Cairns Stingers — WordPress Project Running Sheet

**Owner:** Ryan
**Project:** Cairns Stingers website
**Live URL:** https://www.cairnsstingers.com.au
**Working preview:** https://www.cairnsstingers.com.au/sting/index.html
**Stack:** WordPress (custom theme) + ACF
**Status:** Standalone HTML home page at v11, About section mobile work in progress

---

## ⚡ READ FIRST — workflow rules to keep chats from overflowing

These exist because the project is unusual (single 1.4MB HTML file, frequent edits) and chats keep hitting context limits. Stick to them and chats will run a lot longer.

1. **THIS RUNNING SHEET IS THE SOURCE OF TRUTH.** Don't run `conversation_search` to reconstruct state — every search burns thousands of tokens. Everything you need is below. Memory is for tiny pointers, not narrative.
2. **User uploads `v11-work.html` at chat start.** Don't pull from server with the SiteGround captcha workaround unless explicitly necessary — the captcha solve + curl + grep cycle eats context fast.
3. **Targeted edits only.** When patching, grep for the specific class or block, view 20–40 lines around it, str_replace. Don't view 500-line ranges.
4. **One deploy per task batch, not per patch.** Stage all changes locally, deploy once at end.
5. **Update THIS FILE after each session, not memory.** Memory line edits stack up across chats; the running sheet replaces in place.

---

## 🟢 RESUME HERE next session — About section mobile (5 patches)

Last session (28 Apr ~02:00 UTC): file ingested, exact line numbers located, no edits applied — Ryan ended the session before patches landed. **Pick up by str_replacing the lines below.** v11-work.html is now 2419 lines (the old "~1900" estimate was stale).

**Two open questions still blocking patch 3 + slider build** — ask Ryan first thing:
- **Q3 motion for About main photo:** mask-reveal-on-enter / scroll parallax / ambient scale loop / combination?
- **Q5 slider:** 4 cards confirmed (01 Wednesday Training / 02 The Contest / 03 Mateship / 04 Travel the Coast) — Ryan said "3 image and content blocks" in the original brief which doesn't match. Build for 4 unless he says otherwise.

### Patch 1 — Recruit bring-cards: align text to TOP of number

**Line 595** (inside the `@media (max-width:680px)` block at line ~570):
```css
.bring-card__num{
  margin-bottom:0;line-height:1;font-size:34px;
  grid-row:1 / span 2;align-self:center;   ← change to align-self:start
}
```
One-word change: `center` → `start`. Number sits at the top of the row, h3 + p flow alongside from the top.

### Patch 2 — About top gap (mobile) match recruit bottom

About section padding (line 605): `padding:clamp(120px,16vw,200px) 0;` — same as recruit. The asymmetry Ryan's seeing must come from somewhere else. **Investigate first** before patching:
- Recruit section ends at the bring-cards (`.recruit__cards` has gap:40px on mobile, line 461)
- `.about__inner` has `gap:clamp(72px,10vw,120px)` between split + cards strip (line 613) — could feel oversized on mobile
- The `about__split` mobile gap is 48px (line 623) — fine
- Likely fix: add a mobile override that reduces `.about` top padding only on mobile, e.g. `@media (max-width:680px){.about{padding-top:clamp(72px,12vw,120px)}}` — confirm by eye after patch 1 lands.

### Patch 3 — About main photo: mobile motion (BLOCKED on Q3)

`.about__photo` lives at line 625, `<img>` inside at 633. Currently has only a hover scale (1.02) which doesn't fire on touch. Recommendation when Ryan picks:
- **Mask reveal on enter** — clip-path inset wipe up 0.9s ease-out, triggered by IntersectionObserver — clean, fits the editorial vocab
- **Scroll parallax** — `transform: translateY()` driven by `scrollY` relative to section, range ±40px on mobile. Already used on hero, consistent.
- **Ambient scale loop** — slow infinite `scale(1) → scale(1.06) → scale(1)` over 18s. Works if motion is decorative; risks fighting Ken Burns on Moment image (already in the layout).
- **My pick if he punts:** mask reveal on enter + subtle parallax (±24px). Skip ambient scale to keep it different from Moment.

### Patch 4 — Stinger Life num hover/scroll color: --orl → --ord

**Line 766**:
```css
.moment-card:hover .moment-card__num{color:var(--orl)}   ← change to var(--ord)
```
Note: classes are `.moment-card` / `.moment-card__num` (NOT `.about-card` / `.life-card` despite the section being labelled "Stinger Life"). The Stinger Life cards reuse the moment-card vocab.

Also worth: hover doesn't fire on touch. Add a scroll-triggered active state on mobile so the color change still happens when a card enters viewport. Use IntersectionObserver to toggle `.is-active` on the card; CSS `.moment-card.is-active .moment-card__num{color:var(--ord)}`.

### Patch 5 — Stinger Life cards → mobile horizontal slider (BLOCKED on Q5 count)

Currently `.about__cards` is `grid-template-columns:1fr` at <560px (line 712) — vertical stack. Convert to horizontal slider on mobile:
- **Container:** `display:flex; overflow-x:auto; scroll-snap-type:x mandatory; gap:16px; padding:0 24px; scroll-padding-left:24px;`
- **Each card:** `flex:0 0 78%; scroll-snap-align:start;` — gives the second card a ~22% peek so users see horizontal scroll affordance
- **Hide scrollbar:** `::-webkit-scrollbar{display:none}` + `scrollbar-width:none`
- **Infinite loop:** native CSS scroll snap doesn't loop — needs JS. Two options:
  1. Clone first card to end + last card to start, jump scrollLeft on edge (instant, no animation flicker if done right)
  2. Use a tiny library (Embla, Swiper) — heavier, but Ryan already has Swiper-style hero JS so the pattern's familiar
  - **Recommend option 1**, ~30 lines vanilla JS, fits the no-deps approach of this file
- **Override the existing 560px rule** — keep desktop grid intact, only flip layout on mobile

---

## 🟡 Next big task — port /sting/ to WP theme at root

Currently the home page lives at `cairnsstingers.com.au/sting/index.html` (standalone HTML). Goal: it becomes the WordPress theme's `front-page.php` at root, so `cairnsstingers.com.au/` serves it directly through the active theme `cairns-stingers 11`.

Approach when we get to it:
1. Split `index.html` into theme partials: `header.php`, `front-page.php` (orchestrator), then section partials `template-parts/section-hero.php` / `section-recruit.php` / `section-about.php` / `section-moment.php` / `section-join.php` / `section-sponsors.php` / `footer.php`.
2. Replace hardcoded copy with ACF / WP loops where it makes sense (sponsors, training time, etc. — but only what'll change; keep static stuff inline).
3. Move `/sting/*.mp4` and image assets to `/wp-content/themes/cairns-stingers 11/assets/` (or keep at `/sting/` and reference absolute — your call, /sting/ avoids re-uploading 50MB of video).
4. Settings → Reading → static front page → select the front-page-bound page.
5. Keep `/sting/index.html` as a working preview for ongoing iteration; drop it later.

This is a separate session's work. Don't try to port and keep iterating on design simultaneously.

---

## Project context

Cairns Stingers is an AFL Masters club in Far North Queensland. Site runs on a WordPress custom theme (NOT Estage). Separate codebase to Ryan's primary Estage template work — this is the one project we work in PHP / WordPress / ACF rather than React / Tailwind / @theme tokens.

**Active theme folder:** `cairns-stingers 11` (numbered, NOT bare `cairns-stingers`). WordPress creates new numbered folders on re-upload, the active one can be any. Always check Themes page "Active:" tag before any theme deletion. Manual SiteGround backup before destructive ops — daily auto isn't enough.

**Caching:** SiteGround. Diagnostic step at session start: compare theme version on the live site to local before assuming code is broken. Don't debug PHP if the live site is just stale.

---

## Current build state — v11

**Working file:** `stingers-hero-v11.html` (a.k.a. `v11-work.html` locally) — **2419 lines, 1.4MB**. Iterating in place, not v12/v13.

**Latest deploy:** `905ab88f236c66bfb4853ee086fcfdaf85455ea3b9aafaf9f2c1475ef2df24ae`, 1,403,681 bytes, **2026-04-28 01:19 UTC**.

**Six sections, top to bottom:**
1. **Hero** — cinematic 300vh, 3 chapters, sticky scroll, JS soft-snap. Mobile: horizontal swipeable carousel with dots. Mobile mp4s are CRF 24 / faststart / real first-frame JPG posters.
2. **Recruit / Come and Train** — atmospheric bg-bleed + grain. Watson's Oval details, dual pill CTAs. Eyebrow says "Men & women welcome — even if you're not quite 35 yet".
3. **About — More Than a Team** — top split (photo LEFT empty / copy RIGHT eyebrow `02 ABOUT THE CLUB`) + bottom strip "Stinger life" with 4 cards: `01 Wednesday Training` / `02 The Contest` / `03 Mateship` / `04 Travel the Coast`.
4. **Moment** — full-bleed cinematic photo break (70vh). `stingers-moment.jpg`. 24s Ken Burns zoom.
5. **Join** (CTA, 03) — quiet split. Primary `Register on PlayHQ` pill + sponsor sub-block with `Become a sponsor` ghost.
6. **Sponsors** — editorial logo marquee.

**Latest mobile patches in current live build (01:19 UTC deploy):**
- Slider dots left-aligned at 24px / bottom 34px, "Swipe →" hint hidden
- Recruit eyebrow stripped of pill/dot/blur — plain mono 11px uppercase cyan
- Bring-cards mobile 2-col grid (number left, h3+p right), top rules hidden
- Recruit bg image opacity .55, height 62%, vertical fade into navy

**Empty placeholders:**
- About hero photo (left half of split)
- 4 card photos in Stinger Life strip (still "Photo TBD" pills)

---

## Key line numbers (verified 28 Apr 02:00 UTC against v11-work.html @ 2419 lines)

- **357–599** — Recruit section CSS (mobile patches start at line 568)
- **595** — `.bring-card__num align-self` (Patch 1 target)
- **601–778** — About section CSS
- **605** — `.about` padding declaration
- **613** — `.about__inner` gap (about-split → about-cards spacing)
- **625** — `.about__photo` styles (Patch 3 target area)
- **705–715** — `.about__cards` grid + mobile breakpoints (Patch 5 target area)
- **717–776** — `.moment-card` styles (used by Stinger Life cards)
- **766** — `.moment-card:hover .moment-card__num` color (Patch 4 target)
- **1453+** — Recruit HTML markup
- **2419** — EOF

---

## Design tokens (canonical from style guide)

**Palette:**
- `--or: #F47920` — base orange
- `--ord: #FF5A1F` — darker variant
- `--orl: #ffac53` — lighter variant
- `--cy: #00B4E6` — secondary cyan
- `--cyd: #0091BF` — darker cyan
- `--cyl: #4DD2F2` — lighter cyan
- Background: navy `#0A1628` (NOT black)

**Typography:**
- Headlines / display: Plus Jakarta Sans
- Mono / labels / numbers: JetBrains Mono

**Inclusive language:** No "blokes", "brotherhood", "lads". Use "locals", "mateship", "men and women", "everyone".

**Button vocabulary:**
- Pill primary: solid orange + dark text — main conversion (`.when-cta--primary`, `.join__primary`)
- Pill ghost: transparent outlined — secondary equal-weight (`.when-cta--ghost`, `.join__sponsor__cta`)
- Inline underline: mono caps + border-bottom — quieter actions (`.about__cta`, `.sponsors__cta`)

**Section padding canonical:** `clamp(120px, 16vw, 200px) 0` — applied to recruit/about/join/sponsors. Moment is full-bleed.

---

## ACF / WordPress

**Custom post types:** Players, Games, Contests, Sponsors, Enquiries

**Custom admin pages:** Stingers Settings, AI Studio Agent, Retired (players), Honor Roll

**Known ACF fields:** `game_banner_logo` (image, on Games CPT)

**Site nav:** PLAYERS, GAMES, GALLERY, CLUB SONG, SPONSORS, CONNECT ON SOCIAL (FB), REGISTER. Logo top-left.

---

## Deploy mechanism (for reference; prefer skipping unless needed)

`POST https://cairnsstingers.com.au/sting/_deploy.php` with headers `X-Auth: <secret>` + `X-Target: index.html` + raw body. Secret stored in past chats (search `X-Auth deploy secret` only if absolutely needed — burns context). Allowed extensions: HTML primarily; .mp4 may not be whitelisted.

When SiteGround returns 202 captcha bounce: solve 21-bit SHA1 hashcash on `/.well-known/sgcaptcha/?r=%2Fsting%2F_deploy.php`, parse `_I_=...` from JS body (NOT Set-Cookie), POST on same persistent connection with cookie. Cookie across new connections fails with `ipc:` error.
