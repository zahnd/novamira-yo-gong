---
novamira_id: 2297
title: "Session log"
type: project
description: "One line per work session on yo-gong.com: date, what changed, where it is documented. Newest last."
created: 2026-11-24 04:03:09
updated: 2026-11-24 17:37:34
---

# Session log

One line per session: `YYYY-MM-DD: what was done; what changed on the site; docs/commit`. Newest last.

- 2026-11-23: First MCP audit (read-only, nothing on the site changed). Repo `novamira-yo-gong` initialised (CLAUDE.md, docs/, wpcode-snippets/ mirror of WPCode 337, 338, 1385, 1641, tools/sync). Basic Memory `yo-gong/Website/` seeded. This memory store seeded (Where the docs live, Site rules, Traps on yo-gong.com, Session log).
- 2026-11-23: Comments policy recorded (Stefan): comments are disabled in Settings -> Discussion, WPCode 338 is only WPCode's default sample. Checked read-only: comments closed everywhere, but pingbacks/trackbacks still open (2 pending). Nothing changed on the site; docs/tech-stack.md, docs/custom-code.md, docs/open-items.md #8 updated.
- 2026-11-23: Newsletter "WARTE NICHT AUF DIE VERAENDERUNG" (Flodesk 6ae1f466...) created as DRAFT post 2300 "Warte nicht auf die Veraenderung - laeute Du sie ein" (category Blog, author 2), media 2301-2305 (alt texts by AltText.ai). Not published. New memory 2307 "Newsletter to blog post"; repo docs/newsletter-posts.md + tools/newsletter/flodesk_extract.py.
- 2026-11-23: Post 2300 PUBLISHED (Stefan), /blog/warte-nicht-auf-die-veraenderung-laeute-du-sie-ein/. Nginx Helper purge failed (read-only cache dir); page cache purged via xCloud (site 1c37b5e0..., team Sun Chasing Web Design), verified as guest: home and /blog/ list the post (x-cache MISS). Repo pushed to GitHub.
- 2026-11-24: Read-only performance/image audit (Stefan: images load slowly). PageSpeed mobile 52, LCP 26.4 s. Root cause: 31 Elementor background images (listing cards, headers) delivered as originals (up to 4.9 MB), header image lazy-loaded, Instagram footer at full resolution, no WebP. Nothing changed. Repo docs/performance-audit-2026-11.md, recommendations R1-R7.
- 2026-11-24: Images R1/R2 implemented (Stefan: proceed): size header_1920 registered + generated for 86 images; WPCode 2316 (dynamic backgrounds honour Image Resolution + header preload); 20 Elementor documents got background sizes (backups _backup_elementor_data_20261124); Instagram footer tiles 3:4 (widget CSS). Elementor CSS cleared, xCloud purged, checked in Chrome. PageSpeed mobile 52->64, LCP 26.4->5.9 s; desktop 92. Next: render blocking (R6).
- 2026-11-24: R6 render blocking, safe part (Stefan: proceed): WPCode 2320 (Adobe Fonts preconnect, defer linguise + sonaar scrollbar, footer-only Fluent Forms/Instagram CSS non-blocking). First upload had a stray `<?php` and stopped all everywhere snippets (2316, 1641) for ~4 min (14:15-14:19 UTC); rolled back at once, no form submissions in that window; re-uploaded with a combined parse check. Checked in Chrome (footer forms, Instagram, language switcher, no console errors). PageSpeed mobile 64->67, FCP 2.9->2.6 s, LCP 5.9->5.6 s. Left: 31 blocking stylesheets (needs a plugin or Elementor inline CSS: decision), Adobe Fonts font-display in the Adobe web project, R4, R5, R7.
- 2026-11-24: Decision (Stefan): leave the remaining 31 stylesheets and the Adobe Fonts font-display as they are. Goal is a working, good site, not a perfect score; the client doesn't want to spend much. Nothing changed on the site.
