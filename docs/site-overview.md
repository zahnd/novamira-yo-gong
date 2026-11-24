# Site overview

**www.yo-gong.com** ("Yo-Gong"): yoga, gong/sound and energy work. The site presents retreats
(Schweibenalp, Costa Rica), events, energy coaching, a yoga teacher training (YTT), a podcast and a blog,
and collects newsletter sign-ups (→ Flodesk) and contact requests.

Content is German (`de_CH`, Swiss spelling "ss"); Linguise serves an English machine translation.

_Verified through the Novamira MCP on 2026-11-23._

## Pages

Front page: static, page 19 "Home". No posts page is set (`page_for_posts` = 0); the Blog page 31 lists
posts with a JetEngine listing. Permalinks: `/%category%/%postname%/`.

| ID | Title | Slug | Template |
|---|---|---|---|
| 19 | Home | `/` | Elementor full width (`elementor_header_footer`) |
| 23 | Retreats | `retreats` | Theme Builder 293 "Single Page (header image)" |
| 1856 | Events | `events` | 293 |
| 25 | Energiecoaching (title has a soft hyphen: `Energie&shy;coaching`) | `energiecoaching` | 293 |
| 27 | Yoga Teacher Training (menu label "YTT") | `yoga-teacher-training` | 293 |
| 29 | Podcast | `podcast` | 293 |
| 31 | Blog | `blog` | 293 |
| 33 | Über mich | `uber-mich` | 293 |
| 35 | Kontakt | `kontakt` | 293. **Not built with Elementor** (no `_elementor_edit_mode`) |
| 186 | AGB | `agb` | 1578 "Elementor Single Page" |
| 1566 | Impressum | `impressum` | 1578 |
| 1588 | Cookie-Richtlinie | `cookie-richtlinie` | 1578 (Complianz document) |
| 1589 | Datenschutzerklärung | `datenschutzerklaerung` | 1578 |

All 13 pages are published; there are no drafts.

## Posts

20 published posts in two categories:

- **Blog** (14): personal essays, 2024-03 to 2026-08 (newest 2264 "Es bewegt sich alles in Kreisen", 2026-08-02). They are the Flodesk newsletters, republished: see [newsletter-posts.md](newsletter-posts.md). Draft 2300 (2026-11-23) is the next one.
- **Retreats** (category ID 8, 6 posts): each retreat is a post with the JetEngine meta box "Retreats"
  (dates, location, costs, documents) and is rendered by single template 323 "Retreat". Several
  "Yoga & Klang Retreat Schweibenalp" posts exist, one per year (590 = 2024, 2094 = 2025, 2243 = 2026),
  plus Costa Rica (278, 2098).

## Other content

| Post type | Label | Count | Notes |
|---|---|---|---|
| `event` | Events | 6 | JetEngine CPT, single template 1888 |
| `person` | Personen | 3 | JetEngine CPT, teachers linked from events |
| `jet-cpt-sessions` | Sessions | 3 | JetEngine CPT, energy coaching offers with price |
| `jet-cpt-location` | Location | 2 | JetEngine CPT, linked from retreats |
| `newsletter` | Newsletters | 0 | JetEngine CPT with a URL field, unused |
| `sr_playlist` | Episodes (slug `episode`) | 11 | Sonaar MP3 Audio Player podcast episodes, single template 490 |

Details of fields and queries: [content-model.md](content-model.md).

## Menus

- **main menu** (ID 2): Retreats, Events, Energiecoaching, YTT, Podcast, Blog, Über mich.
- **footer menu** (ID 5): AGB, Impressum, Cookie-Richtlinie, Datenschutzerklärung.

## Forms and newsletter

- Fluent Form **3 "Contact Form"** and Fluent Form **4 "Newsletter"** both sit in the **Footer** template 183,
  so they appear on every page. The Kontakt page 35 has no content of its own: it is the header image
  (template 293) plus the footer with the contact form.
- Form 4 is also in the Newsletter popup 1362. WPCode snippet 1641 forwards each newsletter submission to Flodesk.

See [forms-and-email.md](forms-and-email.md).
