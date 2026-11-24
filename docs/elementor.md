# Elementor

Elementor + Elementor Pro 4.4.1 on Hello Elementor (no child theme). The site is built with **v3 widgets
and containers**: there are no v4 variables or Global Classes (`elementor_global_variables` is unset, the
Kit has no `_elementor_global_classes`). Load the Novamira skills `elementor-build-page` and
`novamira-design` before building, and read `bergwerk/wordpress → elementor/Elementor over MCP` before
writing `_elementor_data`.

_Verified through the Novamira MCP on 2026-11-23._

## Theme Builder templates

| ID | Title | Type | Conditions |
|---|---|---|---|
| 50 | Header | header | entire site |
| 183 | Footer | footer | entire site. Contains Fluent Forms 3 (contact) and 4 (newsletter) and the year shortcode `[wpcode id="1385"]` |
| 1362 | Newsletter | popup | entire site (`include/general`); contains form 4 |
| 293 | Single Page (header image) | single-page | pages 23, 25, 27, 29, 31, 33, 35, 1856 |
| 1578 | Elementor Single Page #1578 | single-page | all other pages (legal pages) |
| 882 | Elementor Single Post #882 | single-post | posts |
| 323 | Retreat | single-post | posts in category 8 (Retreats) |
| 1888 | Event | single-post | `event` |
| 490 | Episode | single-post | `sr_playlist` |
| 247 | Blog Post Loop Item | loop-item | — |
| 913 | Form Thank You | section | confirmation message of form 3 |
| 945 | Newsletter Form Thank You | section | confirmation message of form 4 |
| 1351 | CTA Spende Kaffee | section | — |
| 1827 | Perlentauchen Text | section | — |
| 7 | Default Kit | kit | — |

JetEngine listings (also Elementor documents) are in [content-model.md](content-model.md).

## Background images: always set an Image Resolution

Without an *Image Resolution*, Elementor delivers the **original upload** as a background (up to 4.9 MB
was found). Since 2026-11-24 every background on the site has a size:

| Use | Size |
|---|---|
| Listing cards (845, 1603, 283, 368, 1868) | `featured_landscape` (800×533 crop) |
| Podcast cards (395), event image column (1888) | `large` (1024) |
| Header templates (293, 323, 882, 1578, 1888, 490) and full-width section backgrounds | `header_1920` (1920×1920 box, no crop; registered in Image Regenerate & Select Crop) |

For **dynamic** backgrounds (featured image, JetEngine banner meta), Elementor itself ignores the size.
WPCode **2316** makes it apply; keep it active. Background sizes are not responsive: one file for all
screens. The general mechanism is in `bergwerk/wordpress → elementor/Dynamic background images ignore Image Resolution`.

## Kit 7: global colours

| Title | ID | Value |
|---|---|---|
| Primary | `primary` | `#6EC1E4` (Elementor default, probably unused) |
| Secondary | `secondary` | `#54595F` (Elementor default) |
| Text | `text` | `#444444` |
| Accent | `accent` | `#B5892C` (gold) |
| BG Color | `9fb6c81` | `#F7EFDE` (cream) |
| BG Color 10% / 40% | `31e4644` / `9e016ed` | `#F7EFDE1A` / `#F7EFDE66` |
| BG Green | `8aaf53d` | `#A3B18A` |
| Accent 90% | `1524d80` | `#B5892CE6` |
| White | `1ef10ef` | `#FFFFFF` |
| Transparent | `82a1f50` | `#FFFFFF00` |
| New Item #3 | `6c9d043` | empty (cleanup candidate) |

Use them as `var(--e-global-color-<id>)` or via `__globals__`, never as hard-coded hex.

## Kit 7: global typography

Fonts come from **Adobe Fonts** (Elementor Pro Typekit integration, kit ID set): `freight-display-pro`
(headings and display), `futura-pt` (body), `lulo-one` (secondary). No Elementor custom fonts.

| Title | ID | Family | Weight | Size |
|---|---|---|---|---|
| Primary | `primary` | freight-display-pro | 600 | 36px |
| Secondary | `secondary` | lulo-one | 400 | — |
| Text | `text` | futura-pt | 400 | 19px |
| Accent | `accent` | futura-pt | 600 | 19px |
| Hero Title | `65e93a0` | freight-display-pro | — | 5em |
| H1 … H6 | `0acf947`, `a520e25`, `0484151`, `7ad486c`, `7a453fb`, `f1812fe` | freight-display-pro | — | 3.5 / 2.63 / 2.05 / 1.75 / 1.45 / 1.17em |
| Hero Text | `6617b15` | freight-display-pro | — | 1.8em |
| Button | `eb26f19` | freight-display-pro | — | 20px |
| Intro Text | `f71b580` | freight-display-pro | — | 1.3em |
| Text Small | `af7c932` | (inherits) | — | 0.8em |
| Accent Small | `bb47297` | (inherits) | 600 | 0.8em |

## Kit custom CSS

Kit 7 holds 801 bytes of custom CSS (the WordPress Customizer "Additional CSS" is empty). It is the only
custom CSS on the site besides the widgets' own settings:

```css
/* remove margin from last element */
/*.elementor-widget-container *:last-child {
    margin-block-end: 0px;
}*/

p:last-child {
    margin-block-end: 0px;
}

/* add transition to links */
a, .elementor-button-icon svg {
    transition: 0.2s ease-in-out;
}

strong, b {
    font-weight: 600;
}

/* no line height for icons */
.elementor-icon-wrapper, .elementor-button-icon {
    line-height: 0px;
}

/* fix button alignment */
.elementor-button-content-wrapper {
    align-items: center;
}

/* linguise */
#linguise_popup_container #linguise_popup {
    width: unset !important;
    min-width: unset !important;
}

#linguise_popup_container #linguise_popup ul {
    gap: 50px;
}

@media (max-width: 768px) {
  /* mobile styles */
  #linguise_popup_container #linguise_popup ul {
    gap: 0px;
  }
}
```

After changing templates or Kit CSS: `novamira/elementor-clear-document-cache`, then purge the Nginx cache.
