# Open items

Findings from the first MCP audit (2026-11-23, read-only). **Nothing has been changed on the site.**
Each item needs Stefan's decision before anything is done.

| # | Item | Why it matters | Suggestion |
|---|---|---|---|
| 1 | WPCode 1641 (Flodesk webhook): synchronous cURL without a timeout, TLS verification off, response ignored, duplicate `$url`/`$headers` variables | A slow or failing relay slows down or silently loses newsletter sign-ups | Rewrite with `wp_remote_post` (timeout, TLS on, log non-2xx) or move to `fluentform/submission_inserted`; first confirm the relay on server.bergwerk.li still works (last form 4 entry 2026-07-12) |
| 2 | WPCode 337, 338: default samples WPCode creates on install (draft) | Clutter only; comments are handled in Settings → Discussion, not by 338 | Leave or delete; no effect either way |
| 9 | Nginx Helper can't purge the page cache (read-only file system for PHP on `/etc/nginx/cache/`) | Every publish or edit stays invisible to guests on cached pages (home, /blog/, listings) until someone purges in xCloud | Either purge via xCloud after every change, or switch Nginx Helper's purge method to the xCloud-supported one (check xCloud's docs / its own cache plugin); then verify with `x-cache` |
| 8 | Pingbacks/trackbacks still open: `default_ping_status` open, pingback notification on, all 20 posts `ping_status` open, 2 trackbacks pending in moderation (2026-11-23) | Comments are off in Settings → Discussion, but trackbacks are a comment type and arrive as spam | Untick "Allow link notifications…" and "Attempt to notify…" in Settings → Discussion, close pings on existing posts, delete the 2 pending trackbacks |
| 3 | Cloudinary 3.3.8 installed but inactive | Unused plugin code on the server | Delete it if nobody plans to use it |
| 4 | Kit colour "New Item #3" (`6c9d043`) is empty; Primary/Secondary are the Elementor defaults | Confusing in the colour picker | Remove the empty one; check whether Primary/Secondary are used before renaming |
| 5 | Newsletter CPT (`newsletter`, JetEngine 14) has no posts | Unused content type | Ask whether it is planned |
| 6 | ~~Hosting provider not confirmed~~ | Resolved 2026-11-23: xCloud, team Sun Chasing Web Design, see [tech-stack.md](tech-stack.md) | — |
| 7 | No Feedbucket project checked yet | Client feedback channel | Check whether one exists for yo-gong.com |
