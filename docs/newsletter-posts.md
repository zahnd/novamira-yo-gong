# Newsletter → blog post

Each Flodesk newsletter is republished on the site as a **blog post**. This is a recurring job. The
JetEngine CPT `newsletter` is **not** used for this.

_Derived from posts 2194, 2213, 2264 and first done this way for post 2300 (2026-11-23)._

## How the existing posts are built

| What | Value |
|---|---|
| Post type / status | `post`, created as **draft**. Stefan or the client publish it after review |
| Category | Blog (term 19), also `rank_math_primary_category` = 19 |
| Author | user 2 (`lea`) |
| Title | The newsletter's headline in sentence case (Flodesk sends it in capitals), e.g. "Warte nicht auf die Veränderung – läute Du sie ein" |
| Slug | the title transliterated (`ä` → `ae`), e.g. `warte-nicht-auf-die-veraenderung-laeute-du-sie-ein` |
| Featured image | the newsletter's first (hero) photo. The same ID also goes into JetEngine meta `jet-meta-retreats-image-banner` (every existing post sets both). The single post template shows it as the banner behind the title |
| Intro | the newsletter's **opening line(s)** go into the JetEngine wysiwyg meta `jet-meta-retreats-excerpt` (`<div data-paragraph="true">…</div>`). The template shows it in gold under the banner, so don't repeat it in the body |
| Body | Elementor (`_elementor_edit_mode` builder, type `wp-post`): **one container** with `text-editor` and `image` widgets alternating, one image widget per newsletter photo, `image_size` = `featured_landscape` |
| Text markup | every line is `<div data-paragraph="true">…</div>`; an empty line is `<div data-paragraph="true">&nbsp;</div>` (a **non-breaking** space: a normal space collapses). Emphasis is `<strong>`; the capitalised headline lines stay in capitals |
| Comments | closed (Settings → Discussion) |

## What is left out

The Flodesk logo and header banner, the greeting ("Hola ihr Lieben"), the social-media line and icons, the
offer cards (retreats, online offers, flyer PDF), the signature name, and the footer (address,
unsubscribe). The body ends with the closing line, e.g. "Herzlichst und Pura Vida". Links in the text
that point to yo-gong.com stay as links.

## Procedure

1. Download the web view and extract the text (keep the download in its own scratch folder):
   `curl -sSL -A "Mozilla/5.0" -o <dir>/email.html "https://view.flodesk.com/emails/<id>"`, then
   `python3 -I tools/newsletter/flodesk_extract.py <dir>/email.html`.
   The WebFetch tool / web-fetch agent can't return the full text: it's limited to short quotes.
2. Create the draft (`wp_insert_post`, status draft, author 2, category 19, comments closed), then sideload
   the photos (not the logo) with `media_sideload_image($url, $post_id, null, 'id')`. **AltText.ai writes
   German alt texts on upload.** Check them for "ß" (Swiss spelling: "ss"), English and leaked reasoning
   (`bergwerk/wordpress → alttext-ai/AltText.ai on Swiss sites`).
3. Write the body with `novamira/elementor-set-content` (post_id, the container tree). Then read
   `_elementor_data` back and check that the spacer divs contain U+00A0, and that umlauts and emoji survived.
4. Set `_thumbnail_id`, `jet-meta-retreats-image-banner`, `jet-meta-retreats-excerpt`,
   `rank_math_primary_category` and `post_name`.
5. Look at the preview as admin in Chrome (`novamira/create-admin-access-link`, then `/?p=<id>&preview=true`).
6. Report the draft link, plus any typos in the source text. Keep the author's wording; only fix broken
   formatting markup and proper names (e.g. "Casa Estrelle" → "Casa Estrella"), and say so.
7. When publishing: set the status to publish, then **purge the page cache through xCloud** (`sites.cache.purge`, site `1c37b5e0-…`, team Sun Chasing Web Design). Nginx Helper's purge fails on this server. Check as a guest with `curl -sI` that home and /blog/ return `x-cache: MISS` and list the new post.
8. Add a line to the Novamira Session log and update the table below.

## Published from newsletters

| Post | Title | Flodesk web view | Status |
|---|---|---|---|
| 2300 | Warte nicht auf die Veränderung – läute Du sie ein | `view.flodesk.com/emails/6ae1f466ffaa2a1506843bc4` | **published** 2026-11-24 04:28 site time, `/blog/warte-nicht-auf-die-veraenderung-laeute-du-sie-ein/`, media 2301–2305 |
