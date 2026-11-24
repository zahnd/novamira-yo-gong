# Content model (JetEngine)

JetEngine owns the content model. Change it with the `novamira/jetengine-*` abilities (load the
Novamira skill `jetengine-integration` first) and never with `register_post_type` / `register_meta` in
PHP. General traps: `bergwerk/wordpress → jetengine/JetEngine` (an edit of a CPT **replaces**
`meta_fields`).

_Verified through the Novamira MCP on 2026-11-23 (table `wfzp_jet_post_types`, option `jet_engine_meta_boxes`)._

## Custom post types

| JE ID | Slug | Label | Fields (name:type) |
|---|---|---|---|
| 2 | `jet-cpt-location` | Location | `jet-cpt-location-description`:wysiwyg, `-address`:text, `-country`:text |
| 5 | `jet-cpt-sessions` | Sessions | `jet-cpt-sessions-description`:wysiwyg, `-price`:text, `-type`:select, `-modus`:text, `-sort`:number |
| 14 | `newsletter` | Newsletters | `jet-cpt-newsletter-url`:text (no posts) |
| 15 | `event` | Events | tab `general_settings`: `jet-cpt-event-type`:select, `-interval`:text, `-date`:date, `-date-start`:date, `-banner-image`:media; tab `event_settings`: `-tagline`, `-edition`:text, `-excerpt`, `-description`, `-timeline`, `-catering`, `-what-to-bring`, `-price`, `-location` (all wysiwyg except edition), `-teachers`:posts (→ `person`), `-registration`:wysiwyg, `-documents`:repeater |
| 16 | `person` | Personen | `jet-cpt-person-description`:wysiwyg |

`sr_playlist` (podcast episodes) is registered by Sonaar, not by JetEngine.

## Meta boxes

| Key | Name | On | Fields |
|---|---|---|---|
| `meta-1` | Retreats | posts (used by category Retreats) | `jet-meta-retreats-sorting-priority`:number, `-image-banner`:media, `-excerpt`, `-description`:wysiwyg, `-location`:posts (→ `jet-cpt-location`), `-date`:text, `-date-end`:date, `-included`, `-excluded`, `-costs`:wysiwyg, `-documents`:repeater |
| `meta-2` | Episodes | `sr_playlist` | `jet-meta-episodes-image-banner`:media, `-excerpt`, `-description`:wysiwyg, `-most-recent`:switcher, `-number`:number, `-duration`, `-url-apple`, `-url-spotify`:text |
| `meta-3` | Pages | pages | `jet-meta-pages-image-banner`:media (header image in template 293) |

## Query Builder

| ID | Name | Type |
|---|---|---|
| 3 | All Retreats | posts |
| 4 | Location of current retreat | posts |
| 6 | All Sessions | posts |
| 10 | All Blog Posts | posts |
| 11 | All Podcast Episodes | posts |
| 12 | Related Blog Posts | posts |
| 13 | Documents current post | repeater |
| 17 | All Future Events (einmalig) | posts |
| 18 | Personen for Event | posts |
| 19 | Documents current event | repeater |
| 20 | All future Retreats (non-prio) | posts |
| 21 | All Future Events (fortlaufend) | posts |
| 22 | All future events (merged) | merged-query: 21 + 17, exclude duplicates |
| 23 | All future Retreats (prio) | posts |
| 24 | All future retreats | merged-query: 23 (prio) + 20 (non-prio) |

## Listings (post type `jet-engine`, all Elementor)

| ID | Title | Source |
|---|---|---|
| 283 | Listing Retreat R | posts / post |
| 315 | Listing Location (short) | posts / jet-cpt-location |
| 356 | Page Intro | current post |
| 368 | Listing Session | posts / jet-cpt-sessions |
| 395 | Listing Podcast | posts / sr_playlist |
| 845 | Listing Blog Post | posts / post |
| 1603 | Listing Blog Post Alt | posts / post |
| 1734 | Listing Documents | repeater |
| 1868 | Listing Event R | posts / event |
| 1904 | Listing Person Short | posts / person |
