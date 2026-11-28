---
novamira_id: 2296
title: "Traps on yo-gong.com"
type: project
description: "Non-obvious dependencies on yo-gong.com: Flodesk newsletter webhook in WPCode 1641 tied to Fluent Form 4 field names, year shortcode 1385 in the footer, comments off via Settings > Discussion (not WPCode), Linguise popup CSS in the Kit, server cron, fail2ban, Nginx Helper cannot purge (purge via xCloud), header sticky is JetTricks (Element Pack inactive)"
created: 2026-11-24 04:03:07
updated: 2026-11-24 20:02:03
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
- **Background images need an Image Resolution**, or Elementor delivers the original upload (up to 4.9 MB was found). Since 2026-11-24 all backgrounds have one: cards featured_landscape, podcast/event column large, headers and full-width sections header_1920 (new size, 1920 box, registered in Image Regenerate & Select Crop). For DYNAMIC backgrounds (featured image, JetEngine banner meta) Elementor ignores the size; **WPCode 2316** makes it apply and also preloads the header image. Keep 2316 active. Details: repo docs/performance-audit-2026-11.md.
- **WPCode 2320** (2026-11-24, render blocking R6): preconnects Adobe Fonts, defers the head scripts linguise_switcher and sonaar-music-scrollbar, and loads the Fluent Forms and JetElements Instagram stylesheets without blocking (they are only in the footer and the click-only popup 1362). If a form is ever placed near the top of a page, take the Fluent Forms handles out of 2320's list or it shows unstyled for a moment.
- **WPCode `everywhere` snippets run as one eval()**: one broken snippet stops 1641, 2316 and 2320 together, with no error on the page. Happened 2026-11-24 for 4 minutes (stray `<?php`).
- **Instagram footer** (JetElements, widget 8c17df3 in footer 183): images come from Instagram's CDN at full size via the Graph API; the widget's photo_size setting changes nothing. Tiles are 3:4 via widget custom CSS since 2026-11-24.
- **The sticky header is JetTricks, not Element Pack** (since 2026-11-24; Element Pack Pro is deactivated because its subscription is not renewed). Header 50, container e25d9f5: JetTricks Sticky Column on, Sticky Behavior "Sticky Header" (`fixed`), Sticky Visibility "On Scroll Up", desktop/tablet/mobile, stuck background = global colour 9fb6c81 (#F7EFDE). **Top Spacing must be 0**: JetTricks' default 50 pushes the header 50 px down. Backup of the Element Pack version: option `yg_backup_header50_ep_20261124`. The container custom CSS sets `transform: none; will-change: auto` on the visible stuck state: JetTricks' transform would otherwise trap fixed children (menus, off-canvas, popups) inside the header.

**Why:** these are the places where a harmless-looking change breaks something without an error.
**How to apply:** check this list before touching forms, the footer, the header, comments, WPCode or Linguise.
