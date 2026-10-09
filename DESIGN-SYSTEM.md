# Harrison Accountants — Design System

Source of truth: **Brand Guide.pdf** (Brand Identity Design, 19 pages, supplied by the client). This file records how the guide is applied to the website. Values live in `assets/css/tokens.css`. `assets/css/brand.css` (loaded last) enforces them over the legacy stylesheet. Change brand values in `tokens.css` only.

Stylesheet order (`includes/header.php`): `tokens.css` → `styles.css` → `skeleton.css` → `pages.css` → `brand.css`.

Deliberate deviations: **Trajan Pro is replaced by Cinzel** until a web licence is bought (§3), and **the header keeps its approved translucent sticky design** instead of 50px logo clear space (§1).

## 1. Logo (Guide p.02–05, 09–10)

| Asset | Use | Source |
|---|---|---|
| `assets/harrison-logo.png` | Complete logo on white and light surfaces (header, footer) | Rendered from the guide's vector artwork, p.02 |
| `assets/harrison-logo-reversed.png` | Complete logo on ink/navy/dark backgrounds: white wordmark and H, gold accents | Guide p.04 "Color Version" |
| `assets/favicon.png`, `favicon-32.png` | Browser tab: H mark in an ink circle | Guide p.10 "Web Icon" |
| `assets/icon-light.png` | H mark in a white circle (social/avatar on dark) | Guide p.10 |
| `assets/apple-touch-icon.png` | Home-screen icon: H on white | Guide p.09 "Apps Icon" |
| `assets/h-mark.png` | H mark alone, for the faded watermark only | Guide p.19 |

- The complete logo is always **H icon + HARRISON + ACCOUNTANTS line** together. Don't recolour, outline, stretch, crop or re-typeset it. The H alone is used only as an icon or a faded watermark.
- **Clear space: at least 50px on every side** (`--logo-clear`).
  - **Header (approved exception):** the header keeps the approved design: a translucent band that sits over the page and stays at the top when scrolling, with the logo at 76px (58px on phones). There's about 10px above and below the logo, and ≥50px to the nearest link. The client chose this over the 50px rule, so it should be confirmed with Harrison at sign-off.
  - **Footer:** 50px between the logo and the tagline.
- `harrison-logo.jpg`, `harrison-logo.jpeg` and `harrison-logo-dark.jpg` are the earlier files the client supplied. They're kept for reference but not used.

## 2. Colour (Guide p.08)

| Token | Hex | Role |
|---|---|---|
| `--brand-navy` | `#222D65` | Headings, small labels, key UI, hero scrim |
| `--brand-gold` | `#A08359` | Accent: emphasised words in headings, rules, icons, focus ring |
| `--brand-gold-light` | `#D7BB72` | Accent on dark/navy; primary button fill |
| `--brand-grey` | `#E5E5E5` | Borders, dividers; base of light surfaces |
| `--brand-ink` | `#242E3D` | Body text; dark backgrounds |
| `--brand-indigo` | `#070049` | Error state; deepest tone |
| `--white` | `#FFFFFF` | Header field, cards |

**The rule:** every colour in the site CSS is one of the six brand colours, white, or a **tint** of a brand colour (mixed with white in 5% steps), optionally with transparency. Nothing else.
- **Enforcement:** `python tools/brand-colours.py --check` and the test suite fail on any other value. Running `python tools/brand-colours.py` without `--check` snaps stray colours to the nearest allowed value. Neutral greys map only to white, grey and ink tints, so they never turn gold or navy.
- **Tints in use:**

  | Tint | Hex | Use |
  |---|---|---|
  | grey 35% | `#F6F6F6` | Page surface |
  | grey 60% | `#EFEFEF` | Alternate sections |
  | grey 25% | `#F8F8F8` | Raised sections |
  | ink 70% | `#666D77` | Secondary text, 5.3:1 on white |
  | ink 80% | `#505864` | Lead text |
  | ink 55% | `#878C94` | Disabled and inactive only |

**Status colours (deliberate exception):** forms use conventional red and green because error and success states need them to be understood at a glance. They are tokens in `tokens.css`, so the brand-colour check ignores them and they appear nowhere else:

| Token | Hex | Use |
|---|---|---|
| `--color-error` | `#B3261E` | Required asterisks, missing-field outline and message, error status line |
| `--color-error-tint` | `#FDECEA` | Fill of a missing field |
| `--color-success` | `#1B7A3D` | The "Sent" button and confirmation line (white text on it: 5.5:1) |
| `--color-success-tint` | `#E7F4EC` | Reserved for success backgrounds |

Colour is never the only signal: errors also carry an "!" and a text message, and the button changes its label to "Sent" with a check mark.

Accessibility rules:
- Gold on white is about 3.6:1. It's fine for headings, which count as large text, and for graphics, but **not for small text**. Small labels are navy and notes are ink.
- Gold on navy and light gold on navy are used only for large text and buttons.
- The error state is red (see Status colours) with a 2px outline, an "!" and a text message, never colour alone.

## 3. Typography (Guide p.06–07)

Only the guide's five styles exist on the site. `brand.css` resets every element to inherit, then applies:

| Style | Face | Size | Weight | Used for |
|---|---|---|---|---|
| H1 | Primary | **34pt** | Bold | Page title (one per page) |
| H2 | Primary | **22pt** | Bold | Section headings, image numbers |
| H3 | Primary | **18pt** | Bold | Card and list titles (including pinned-list items, related cards) |
| Body | Roboto | **14pt** | Medium (500) | Paragraphs, buttons, form fields, menu |
| Description | Roboto | **12pt** | Regular (400) | Labels, desktop nav, breadcrumbs, captions, notes, footer links |

- **Primary face:** Trajan Pro is a licensed Adobe font. The stack is `"Trajan Pro 3", "Trajan Pro", Cinzel, serif`. **Cinzel** (SIL OFL, self-hosted in `assets/fonts/`) is used until Harrison buys a web licence (Adobe Fonts / MyFonts). Once a Trajan kit is added it takes over automatically.
- **Headings:** navy, no italic. Emphasised words (`em`, `.gold-word`) stay upright in gold. On the Home hero the copy is white and light gold over the navy scrim.
- **Bold body text:** `strong` / `b` use Roboto Bold (a brand weight).
- **Tracking:** small uppercase labels get light tracking (`.08em`) for legibility. The guide doesn't specify tracking, so nothing else is tracked.
- **Sizes:** sizes are fixed in pt as the guide specifies (34pt = 45px). Pages never go below these.

## 4. Components

| Component | Notes |
|---|---|
| Header | Translucent band, sticky on scroll (approved design). Logo, Home · Services · About · News · Contact (description style), and a primary "Book an appointment" button. Services opens a two-column mega-menu (Who We Help: 10 · What We Provide: 13), with a gold rule under each column title. About opens Who We Are · Testimonials · Privacy & Cookies Policy. Both open on hover, focus or click and close on Escape. |
| Detail page (`includes/detail-page.php`) | Hero (breadcrumb, eyebrow, H1, lead), article with optional photo and body blocks, and a sticky sidebar with a list of sibling pages (current page marked by a gold bar) plus a contact card. Then related services and the CTA band. |
| Buttons | Primary: light-gold fill, ink text, pill. Secondary: outline. Body style. Gold focus ring. |
| Cards | White, 16–30px radius, grey/gold hairline, soft navy shadow (`--shadow-card`). |
| Pinned-scroll section (`includes/pin-section.php`) | Images scroll left, list pinned right. Active item: white pill, filled gold circle marker. Inactive: outlined marker, ink-55% title. Faded H-mark watermark behind (p.19). Stacks on phones. |
| 3D H mark (`assets/js/h-mark-3d.js`) | Home closing section only. The H from the Brand Guide's vector artwork (`assets/images/h-mark.json` / `.svg`), extruded: navy stems and a brushed-gold swoosh set slightly forward. Neutral tone mapping keeps the brand colours true. It sways gently and tilts towards the cursor. three.js (pinned version, one shared import map in `includes/header.php`) loads only when the section nears the viewport. The SVG shows as a fallback without WebGL. A still frame shows under reduced motion, and on phones it's a faded watermark. |
| Particle H (`assets/js/h-particles.js`) | Who We Are hero, repeated on Home above the FAQ (it assembles the first time the section comes into view). About 6,000 navy and gold particles (2,600 on phones) sampled from the H outline, weighted to the edges for crispness. They gather into the H on arrival (~2.6s), part around the cursor, and scatter as the hero scrolls away. About 8% stay loose as drifting "numbers". The hero photo is the no-WebGL fallback and is hidden from the start so it never flashes. Reduced motion shows the finished H. |
| Gold ribbon (`assets/js/gold-ribbon.js`) | Services hero, plus a `band` divider across the Home seam between the hero and About (`data-gold-ribbon="band"`, which flows with page scroll and never fades). The swoosh as a twisting brushed-gold ribbon with a thin navy strand, placed in the measured gap between the headline and the service list (an orthographic camera works in CSS pixels), with its swing kept inside that gap. It unrolls left to right, flows as you scroll, and ripples under the cursor. |
| Dot field (`assets/js/dot-field.js`) | Navy dots that part around the cursor and turn gold; edges fade out. The first time the cursor enters each field, the dots rush in from all sides and gather in a ring around it (~1.6s), then normal push-away resumes. Static under reduced motion. |
| Scroll typewriter / scroll-blur lines | Home headings type in; the About statement sharpens line by line. |
| Section boundaries | No hard edges: photo sections dissolve into the surface; decorative layers fade at their edges. |

## 5. Motion

Restrained: reveals, gentle hover zoom (4–6%), pinned-list transitions. Everything works without JavaScript or the animation CDNs, and switches off under `prefers-reduced-motion`.

**Site-wide scroll animation (`assets/js/scroll-fx.js`)** runs automatically on every page, with no per-page markup:

- Headings, cards, article text, list items, form fields and footer columns fade up (14–32px) in a staggered cascade (90ms steps, capped at 450ms).
- Photos reveal with a soft wipe and a 14% zoom-out, then drift up to ±24px against the scroll (parallax).
- A 3px gold progress bar runs along the top of the window.
- It animates only `opacity`, `translate`, `scale` and `clip-path`, so hover effects are untouched, and each element drops its animation classes when its entrance is done.
- Fail-safes: nothing is hidden unless the script runs (a snippet in the page head pre-hides only the title area, and gives up after 3s). Anything scrolled past unseen is shown at once, a sweep reveals anything missed at the page edge, and reduced motion turns it all off.
- Sections with their own motion are excluded: the pinned lists, the ribbon band, the Home hero, and the typewriter and blur-line headings.

## 6. Accessibility

Text contrast ≥ 4.5:1 (small) / 3:1 (large). Visible focus on every control. Menus close on Escape. Collapsed content is `inert`. Decorative canvases and images are `aria-hidden` / empty `alt`.

## 7. Open items for Harrison

- Trajan Pro web licence, or written approval to keep Cinzel.
- Original vector logo files (SVG/AI) for print. The web PNGs here were rendered from the guide.
- One photograph per service for the pinned sections (currently two images reused).
