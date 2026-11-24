# WPCode snippets: mirror of the live custom code

Byte-exact copy of **every** WPCode snippet on www.yo-gong.com, active and inactive (WPCode Lite 2.4.0),
pulled through the Novamira MCP. **The site is the source of truth.** These files are for reading,
diffing and review. To change a snippet, change it on the site ([docs/custom-code.md](../docs/custom-code.md)),
then re-sync with [tools/sync](../tools/sync/README.md).

`manifest.json` holds each snippet's WPCode settings (status, type, location, priority, conditional logic)
and `live_sha1`: the sha1 of the code exactly as stored in `post_content` on the server.

The files are normalised, so their own sha1 differs from `live_sha1`:

- CRLF → LF (snippets saved through the WPCode admin textarea are stored with CRLF; see `live_has_crlf`),
- PHP files get a leading `<?php` line (WPCode stores PHP without it and `eval()`s it),
- every file ends with exactly one newline (WPCode stores none).

| ID | File | Type | Location | Status |
|---|---|---|---|---|
| 337 | `337-display-a-message-after-the-1st-paragraph-of-posts.txt` | text | after_paragraph | inactive (draft) |
| 338 | `338-completely-disable-comments.php` | php | everywhere | inactive (draft) |
| 1385 | `1385-dynamic-year-copyright-shortcode.php` | php | shortcode `[wpcode id="1385"]` | active |
| 1641 | `1641-subscribe-user-in-flodesk-using-webhook.php` | php | everywhere | active |

What each one does: [docs/custom-code.md](../docs/custom-code.md). Last pulled 2026-11-23.
