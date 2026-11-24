# Custom code

All custom PHP/JS/CSS on www.yo-gong.com is in two places:

1. **WPCode Lite 2.4.0** snippets: mirrored byte-exactly in [wpcode-snippets/](../wpcode-snippets/README.md).
2. The **Elementor Kit 7 custom CSS** (801 bytes): quoted in [elementor.md](elementor.md#kit-custom-css).

There is no child theme, no mu-plugin and no Customizer CSS. _Verified 2026-11-23._

## WPCode snippets

| ID | Title | Type | Location | Status | What it does |
|---|---|---|---|---|---|
| 337 | Display a message after the 1st paragraph of posts | text | after_paragraph | inactive (draft) | Default sample WPCode creates on install ("Thank you for reading…"). Unused |
| 338 | Completely Disable Comments | php | everywhere | inactive (draft) | Default sample WPCode creates on install (library ID 12). Unused: comments are disabled in **Settings → Discussion** instead (see [tech-stack.md](tech-stack.md#comments)) |
| 1385 | Dynamic Year Copyright Shortcode | php | shortcode only (`auto_insert` 0) | **active** | `echo "&#169; " . date('Y')`. Used as `[wpcode id="1385"]` in Footer 183 |
| 1641 | Subscribe user in Flodesk using Webhook | php | everywhere | **active** | Forwards Fluent Form 4 sign-ups to the Flodesk webhook relay. See [forms-and-email.md](forms-and-email.md#newsletter--flodesk) |
| 2316 | YG: Bildgrössen für Hintergrundbilder + Header-Preload | php | everywhere | **active** (2026-11-24) | Makes Elementor honour a background's *Image Resolution* also for dynamic tags (cards, headers), and preloads the header image. See [performance-audit-2026-11.md](performance-audit-2026-11.md#implemented-2026-11-24). **Deactivating it brings back the original-size card and header images** |
| 2320 | YG: Render-Blocking reduzieren | php | everywhere | **active** (2026-11-24) | Front end only: preconnects to Adobe Fonts (`use.typekit.net`, `p.typekit.net`), defers the head scripts `linguise_switcher` and `sonaar-music-scrollbar` (WordPress strategy API), and loads the footer-only stylesheets (3× Fluent Forms, 2× JetElements Instagram) without blocking (`media="print"` + onload). See [performance-audit-2026-11.md](performance-audit-2026-11.md#r6-render-blocking-2026-11-24). If a form is ever placed high on a page, take the Fluent Forms handles out of its list |

Option `wpcode_snippets` (what loads automatically) holds 2320, 2316 and 1641 under `everywhere`; 1385 runs only
through its shortcode. The WPCode trash is empty.

## Changing a snippet

The general procedure and its traps are canonical in `bergwerk/wordpress → wpcode/WPCode Pro` (also true for Lite)
and `bergwerk/wordpress → wpcode/WPCode delete_cache stops every snippet until rebuilt`. In short:

1. Compare the live `sha1(post_content)` with `live_sha1` in `wpcode-snippets/manifest.json`. If it differs,
   someone changed it on the site: re-sync first.
2. Edit the repo file. Send the code base64-encoded, back up the old `post_content`, write it with a raw
   `$wpdb->update`, and compare sha1 (`WPCode_Snippet->save()` strips one backslash level).
   **Store PHP without the `<?php` line.** The repo files have it (the mirror adds it); strip it before upload.
   WPCode Lite **concatenates all `everywhere` PHP snippets into one `eval()`**: a parse error in one snippet
   (a stray `<?php` is enough) silently stops **all** of them, including the Flodesk webhook 1641. It happened
   on 2026-11-24 for 4 minutes. Lint the file locally (`php -l`), and before activating parse the combined code:
   `token_get_all('<?php '.implode(PHP_EOL, $codes), TOKEN_PARSE)` over `(new WPCode_Snippet($id))->get_code()`
   of every active `everywhere` snippet, in the order of `get_option('wpcode_snippets')['everywhere']`.
3. `wpcode()->cache->delete_cache(); wpcode()->cache->cache_all_loaded_snippets();` **in the same request**.
4. Verify in a **new** request as a guest, and check that **the other snippets still work** too (e.g. 2316's
   `<link rel="preload" as="image">` on the home page). If not, set the new snippet back to draft and
   rebuild the cache at once.
5. Re-sync the mirror ([tools/sync](../tools/sync/README.md)), update this table, commit.

Name new functions with a `yg_` prefix (all snippets share one namespace). The repo mirror, not WPCode's
revision history, is the backup to restore from.
