---
novamira_id: 2296
title: "Traps on yo-gong.com"
type: project
description: "Non-obvious dependencies on yo-gong.com: Flodesk newsletter webhook in WPCode 1641 tied to Fluent Form 4 field names, year shortcode 1385 in the footer, comments off via Settings > Discussion (not WPCode), Linguise popup CSS in the Kit, server cron, fail2ban, Nginx Helper cannot purge (purge via xCloud)"
created: 2026-11-24 04:03:07
updated: 2026-11-24 04:31:32
---

# Traps on yo-gong.com

Non-obvious things on www.yo-gong.com (first MCP audit 2026-11-23).

- **Newsletter -> Flodesk runs through WPCode 1641**, not through a Fluent Forms integration. On `fluentform/before_insert_submission` for **form 4 "Newsletter"** it POSTs `{first_name, email}` to `https://server.bergwerk.li:9000/hooks/yogong-flodesk` (Bergwerk webhook relay) with SSL verification off and without checking the response. It reads the field names `names[first_name]` and `email`: renaming those fields in form 4 silently stops Flodesk sign-ups. Form 4 is placed in the Footer template 183 and the Newsletter popup 1362.
- **WPCode 1385** (shortcode `[wpcode id="1385"]`, prints "(c) <year>") is used in the Footer template 183. Deactivating it leaves a raw shortcode in the footer.
- **Comments are disabled in Settings -> Discussion** (Stefan, 2026-11-23), not by code. WPCode 337 and 338 ("Completely Disable Comments") are the default samples WPCode creates on install, inactive and irrelevant. Pingbacks/trackbacks were still open on 2026-11-23 (open item 8 in the repo).
- **Linguise switcher popup** is styled in the Elementor Kit custom CSS (`#linguise_popup_container`), not in Linguise settings.
- **WP-Cron is disabled** (`DISABLE_WP_CRON`); a server cron runs it (no overdue events on 2026-11-23). If scheduled things stop, check the server cron first.
- **WP fail2ban is active.** Don't request `/?author=N` or `/wp-json/wp/v2/users` from the server itself (loopback): it bans the server IP. See `bergwerk/wordpress -> debugging/Server-side enumeration probes get the server banned by fail2ban`.
- Mail goes through **FluentSMTP -> Postmark** from daniela@yo-gong.com.
- **Nginx Helper cannot purge the page cache**: its unlink() on /etc/nginx/cache/www.yo-gong.com/... fails with "Read-only file system" (seen 2026-11-23 when publishing post 2300). Home and /blog/ stay stale for guests (x-cache: HIT) until the cache is purged through xCloud. Hosting: xCloud, team Sun Chasing Web Design (not Bergwerk), server SCWD Prod 1.

**Why:** these are the places where a harmless-looking change breaks something without an error.
**How to apply:** check this list before touching forms, the footer, comments, WPCode or Linguise.
