# Performance and image audit (2026-11-24)

> **Update 2026-11-24 afternoon: R1, R2 and the Instagram aspect ratio are implemented.** Results and
> details are in [Implemented](#implemented-2026-11-24) at the end. R3 turned out to save no bytes (see there).

Triggered by Stefan: "images seem to be pretty big as they seem to load long". The audit was **read-only**:
nothing on the site was changed. Sources: Novamira MCP (media library, Elementor data, settings), guest
requests with curl over 11 pages, and an xCloud PageSpeed scan (Lighthouse, mobile). Image re-encodes were
measured in memory on the server and not saved.

## Result in one line

The site ships **original-size photos as CSS background images** (headers, blog/retreat/event cards). That
makes mobile **LCP 26.4 s** (Google's target is under 2.5 s). Fixing the image sizes alone would cut most
pages by 1–5 MB.

## PageSpeed (xCloud scan, home page, mobile, 2026-11-24 12:35 UTC)

| Metric | Value |
|---|---|
| Performance score | **52** (SEO 100, Accessibility 94, Best practices 100) |
| Largest Contentful Paint | **26.4 s** |
| Speed Index | 11.2 s |
| First Contentful Paint | 2.9 s |
| Total Blocking Time | 370 ms |
| CLS | 0.007 (good) |
| Top insights | render-blocking requests (est. −4.1 s FCP), image delivery (est. −1.3 MB, −6.5 s LCP), LCP image not discoverable in the HTML and lazy-loaded, font-display (Adobe Fonts), 280 KB unused JS, 130 KB unused CSS |

## Findings

### 1. Background images are delivered as originals (biggest problem)

31 Elementor background images on the site. **None has an image size set** except the podcast listing 395
(`large`), so the browser gets the uploaded original:

| Where | What | Example (delivered) |
|---|---|---|
| JetEngine listings 845 "Blog Post", 1603 "Blog Post Alt", 283 "Retreat R", 368 "Session", 1868 "Event R" | card background = `post-featured-image`, full size | Home and /blog/: card of post 2194 loads **4,890 KB** (`yo-gong-daniela-08894.jpg`, 4536×6804 px) for a card about 400 px wide |
| Theme Builder headers 293, 323, 882, 1578, 1888 | header background = JetEngine `jet-meta-…-image-banner` via `jet-post-custom-image`, full size | /events/ and Costa Rica retreat: 1,633 KB; /retreats/: 1,407 KB; /podcast/ and /kontakt/: 1,078 KB |
| Static backgrounds on pages 19, 25, 27, 33 and retreat posts 278, 590, 2094, 2098, 2243 | chosen from the library, full size | Home: 1,352 KB, 917 KB, 547 KB … |

Measured savings with the same photos re-encoded on the server (not saved):

| Image | Use | Now | JPEG q82 at that size | WebP q80 |
|---|---|---|---|---|
| 2195 `yo-gong-daniela-08894.jpg` | card 800×533 | 4,890 KB | 56 KB | **24 KB** |
| 2195 same | header 1920 w | 4,890 KB | 578 KB | 196 KB |
| 539 `website-daniela-09090.jpg` | header 1920 w | 1,633 KB | 653 KB | 391 KB |
| 1702 `yo-gong-daniela-07887.jpg` | header 1920 w | 6,761 KB | 1,214 KB | 604 KB |
| 1981 PNG screenshot | content 1024 w | 2,951 KB (1,640 KB at 1024) | 226 KB | 175 KB |

**Why there is no size to pick for headers:** Image Regenerate & Select Crop has `medium_large` (768),
`1536x1536` and `2048x2048` on its **global ignore list**, so those copies are never generated. Between
`large` (1024) and the original there is nothing, which suits neither a full-width header nor a retina card.

### 2. The header (LCP) image is lazy-loaded

Elementor's "Lazy load background images" is **on**. It also applies to the header container, so the LCP
image only starts loading after Elementor's JS has run ("LCP request discovery" fails). Nothing preloads it,
and there is no `fetchpriority` anywhere on the page.

### 3. Media library

155 images: 120 JPEG, 12 PNG, 20 SVG, 3 WebP. Originals total **150 MB**, generated sizes 76 MB.
- 57 originals are wider than 2000 px, 4 wider than 3000 px. Some bypassed WordPress' 2560 px scaling:
  2195 (4536×6804, 4.9 MB) and 2197 (4091×6137, 5.7 MB). 1702 is 6.8 MB at 1707×2560.
- PNG screenshots of 1.4–3.0 MB (1981, 1207, 1294, 1188, 149, 259) are used as photos.
- The server can write **WebP and AVIF** (Imagick), but the site doesn't generate them
  (`image_editor_output_format` is not set). JPEG quality is the default 82.
- Browser caching of images is fine: `Cache-Control: max-age=315360000, public`.

### 4. Instagram feed in the footer (every page)

The JetElements Instagram Gallery (footer 183, widget `8c17df3`, 6 columns) loads `photo_size: standard`
(Instagram's ~1080 px files, 130–390 KB each), about **1.35 MB** per page for small thumbnails. They are
`loading="lazy"`, so they load when the visitor scrolls to the footer.

### 5. Render blocking (secondary)

36 stylesheets in `<head>` (Elementor, Element Pack, JetElements, JetEngine, JetTricks, Fluent Forms on every
page, Complianz, Linguise, Adobe Fonts `use.typekit.net`). 34 scripts without `defer`/`async`. HTML is 277 KB.
The Adobe Fonts CSS doesn't use `font-display: swap`; that is set in the Adobe Fonts web project, not in
Elementor (`elementor_font_display` = swap only covers Google Fonts).

### 6. Housekeeping found along the way

- `uploads/linguise/e200cec1…/cache`: an old Linguise translation cache (533 files, 82 MB, last written
  2024-11-10). The active cache is `8f2dbb54…` (200 MB). The `.php` files are Linguise cache, **not** malware.
- Podcast MP3s take 305 MB of uploads (expected).

## Recommendations (in order of effect ÷ risk)

| # | Change | Effect | Risk / effort |
|---|---|---|---|
| R1 | Give every background image a size: cards → `featured_landscape` (800×533) or `large`; headers → a new ~1920 px size (or re-enable `1536x1536`/`2048x2048` in Image Regenerate & Select Crop and regenerate) | Biggest win: home −5 MB, header pages −1 MB each, LCP far lower | Low. Elementor settings on ~31 elements, back up `_elementor_data` first, then clear the CSS cache and purge xCloud |
| R2 | Don't lazy-load the header background, and preload it (`<link rel="preload" as="image" fetchpriority="high">` via a WPCode snippet that reads the banner meta), or exclude the header container from lazy loading | LCP starts immediately | Low |
| R3 | Instagram footer: `photo_size` → `thumbnail`/`low` | −1 MB per page when scrolled | Very low, one setting |
| R4 | Shrink oversized originals (> 2560 px or > 1 MB) to 2560 px at q82 and convert PNG photos to JPEG, with backups | Smaller library and smaller fallbacks; fixes 2195/2197/1702 | Medium: files are replaced in place, so back them up first |
| R5 | Generate WebP for new uploads (`image_editor_output_format` jpeg→webp in WPCode) and regenerate the sizes; or deliver via a CDN (Cloudinary is installed but inactive; see `bergwerk/wordpress → cloudinary/` for its Elementor traps first) | −40–60 % more per image | Medium. Test on one post first |
| R6 | Render blocking: Elementor performance features (element caching, optimized CSS/asset loading, inline font icons), load Fluent Forms CSS only where a form is, `font-display: swap` in the Adobe Fonts project | FCP −1–4 s | Medium, needs a visual check |
| R7 | Delete the stale Linguise cache folder `e200cec1…` (82 MB) | Disk only | Low; confirm first |

Measure again with xCloud PageSpeed (`sites.pagespeed.scan`) after R1–R3.

## Implemented 2026-11-24

Approved by Stefan ("proceed"), together with a change to the Instagram feed's aspect ratio.

| Step | What was done |
|---|---|
| New image size | `header_1920` (1920×1920 box, no crop) registered in **Image Regenerate & Select Crop** (option `sirsc_use_custom_image_sizes`; backup `_backup_sirsc_use_custom_image_sizes_20261124`). Generated for the 86 images larger than 1920 px. New uploads get it automatically. Smaller images fall back to the original, which is ≤ 1920 px |
| WPCode **2316** "YG: Bildgrössen für Hintergrundbilder + Header-Preload" (PHP, everywhere) | (1) Elementor ignores a background's *Image Resolution* when the image comes from a dynamic tag. The snippet remembers the element being parsed (`elementor/element/before_parse_css`) and applies its stored `background_image[size]` in `elementor/files/css/property`. (2) It prints `<link rel="preload" as="image" fetchpriority="high">` for the header image of pages (`jet-meta-pages-image-banner`), posts (`jet-meta-retreats-image-banner`) and events (`jet-cpt-event-banner-image`), and for the first container's background on full-width pages (Home). Mirrored in `wpcode-snippets/` |
| Background sizes set (20 documents, backups in post meta `_backup_elementor_data_20261124`) | Cards → `featured_landscape`: listings 845, 1603, 283, 368, 1868. Event image column 1888/ec3aba6 → `large`. Headers → `header_1920`: templates 293, 323, 882, 1578, 1888 and Episode 490. Static section backgrounds → `header_1920`: pages 19, 25, 27, 33, retreat posts 278, 590, 2094, 2098, 2243. Podcast listing 395 keeps `large` (now honoured) |
| Instagram feed (footer 183, widget `8c17df3`) | Widget custom CSS: `aspect-ratio: 3 / 4`, `height: auto !important` instead of the fixed 300 px height (backup as above). Matches Instagram's own profile grid; reels (9:16) and 4:5 posts lose much less |
| R3 (photo size) dropped | With the Instagram Graph API the widget always loads `media_url` (the full file from Instagram's CDN, 640×1136 to 1206×2144). `photo_size` only sets the `width`/`height` attributes. Nothing to gain there |
| Caches | Elementor CSS + element cache cleared, xCloud page cache purged, 13 pages warmed as a guest |

### Result

| | Before (mobile) | After (mobile) | After (desktop) |
|---|---|---|---|
| Performance | 52 | **64** | **92** |
| LCP | 26.4 s | **5.9 s** | 1.2 s |
| Speed Index | 11.2 s | 9.9 s | 2.5 s |
| Time to Interactive | 37.4 s | 12.9 s | 2.5 s |
| TBT | 370 ms | 120 ms | 30 ms |
| Image-delivery savings left | 1.3 MB | 109 KB | 89 KB |
| LCP request discovery | fails | **passes** | passes |

Images referenced per page (guest, curl): Home 10.0 → 2.2 MB, /blog/ 8.4 → 2.2 MB, /retreats/ 4.9 → 2.1 MB,
/events/ 3.8 → 2.1 MB, /energiecoaching/ 4.3 → 1.7 MB (about 1.35 MB of each is the lazy-loaded Instagram feed).

Checked in Chrome: Home header, blog cards, event header and the Instagram tiles (277×370 = 3:4) render
correctly, and the Kit's global colours are intact.

### Still open

- **R6 render blocking** is now the biggest item on mobile (est. −4.3 s FCP).
- R4 (oversized originals, PNG screenshots, e.g. the 1.6 MB PNG on the Costa Rica retreat page), R5 (WebP), R7 (stale Linguise cache).
- **Rule from now on:** every new background image in Elementor gets an *Image Resolution* (cards `featured_landscape`, full-width sections `header_1920`). Without one, Elementor delivers the original.
