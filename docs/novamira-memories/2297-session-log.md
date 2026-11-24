---
novamira_id: 2297
title: "Session log"
type: project
description: "One line per work session on yo-gong.com: date, what changed, where it is documented. Newest last."
created: 2026-11-24 04:03:09
updated: 2026-11-24 13:37:44
---

# Session log

One line per session: `YYYY-MM-DD: what was done; what changed on the site; docs/commit`. Newest last.

- 2026-11-23: First MCP audit (read-only, nothing on the site changed). Repo `novamira-yo-gong` initialised (CLAUDE.md, docs/, wpcode-snippets/ mirror of WPCode 337, 338, 1385, 1641, tools/sync). Basic Memory `yo-gong/Website/` seeded. This memory store seeded (Where the docs live, Site rules, Traps on yo-gong.com, Session log).
- 2026-11-23: Comments policy recorded (Stefan): comments are disabled in Settings -> Discussion, WPCode 338 is only WPCode's default sample. Checked read-only: comments closed everywhere, but pingbacks/trackbacks still open (2 pending). Nothing changed on the site; docs/tech-stack.md, docs/custom-code.md, docs/open-items.md #8 updated.
- 2026-11-23: Newsletter "WARTE NICHT AUF DIE VERAENDERUNG" (Flodesk 6ae1f466...) created as DRAFT post 2300 "Warte nicht auf die Veraenderung - laeute Du sie ein" (category Blog, author 2), media 2301-2305 (alt texts by AltText.ai). Not published. New memory 2307 "Newsletter to blog post"; repo docs/newsletter-posts.md + tools/newsletter/flodesk_extract.py.
- 2026-11-23: Post 2300 PUBLISHED (Stefan), /blog/warte-nicht-auf-die-veraenderung-laeute-du-sie-ein/. Nginx Helper purge failed (read-only cache dir); page cache purged via xCloud (site 1c37b5e0..., team Sun Chasing Web Design), verified as guest: home and /blog/ list the post (x-cache MISS). Repo pushed to GitHub.
- 2026-11-24: Read-only performance/image audit (Stefan: images load slowly). PageSpeed mobile 52, LCP 26.4 s. Root cause: 31 Elementor background images (listing cards, headers) delivered as originals (up to 4.9 MB), header image lazy-loaded, Instagram footer at full resolution, no WebP. Nothing changed. Repo docs/performance-audit-2026-11.md, recommendations R1-R7.
