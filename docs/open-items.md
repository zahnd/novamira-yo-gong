# Open items

Findings from the first MCP audit (2026-11-23, read-only). **Nothing has been changed on the site.**
Each item needs Stefan's decision before anything is done.

| # | Item | Why it matters | Suggestion |
|---|---|---|---|
| 1 | WPCode 1641 (Flodesk webhook): synchronous cURL without a timeout, TLS verification off, response ignored, duplicate `$url`/`$headers` variables | A slow or failing relay slows down or silently loses newsletter sign-ups | Rewrite with `wp_remote_post` (timeout, TLS on, log non-2xx) or move to `fluentform/submission_inserted`; first confirm the relay on server.bergwerk.li still works (last form 4 entry 2026-07-12) |
| 2 | WPCode 337, 338: unused library samples (draft) | Clutter. 338's title suggests that comments are disabled, but it's inactive | Delete both, or activate 338 if comments really should be off |
| 3 | Cloudinary 3.3.8 installed but inactive | Unused plugin code on the server | Delete it if nobody plans to use it |
| 4 | Kit colour "New Item #3" (`6c9d043`) is empty; Primary/Secondary are the Elementor defaults | Confusing in the colour picker | Remove the empty one; check whether Primary/Secondary are used before renaming |
| 5 | Newsletter CPT (`newsletter`, JetEngine 14) has no posts | Unused content type | Ask whether it is planned |
| 6 | Hosting provider not confirmed (hostname `scwd-prod-1`) | Needed for cache, cron and backup questions | Look it up in xCloud and record it in [tech-stack.md](tech-stack.md) |
| 7 | No Feedbucket project checked yet | Client feedback channel | Check whether one exists for yo-gong.com |
