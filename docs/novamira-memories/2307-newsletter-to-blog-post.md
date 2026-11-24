---
novamira_id: 2307
title: "Newsletter to blog post"
type: project
description: "Recurring job on yo-gong.com: each Flodesk newsletter becomes a draft blog post (category Blog, author 2, hero = featured image + banner meta, opening line in jet-meta-retreats-excerpt, Elementor text/image body)"
created: 2026-11-24 04:23:56
updated: 2026-11-24 04:31:32
---

# Newsletter to blog post

Each Flodesk newsletter is republished as a **blog post** (not the JetEngine `newsletter` CPT). Recurring job, first done this way for post 2300 on 2026-11-23, modelled on posts 2194, 2213 and 2264.

- Draft post, category Blog (19, also `rank_math_primary_category`), author user 2, comments closed. Title = the newsletter headline in sentence case.
- Hero photo -> `_thumbnail_id` and `jet-meta-retreats-image-banner` (same ID). Opening line(s) -> `jet-meta-retreats-excerpt` (shown in gold under the banner, not repeated in the body).
- Body: Elementor, one container, `text-editor` and `image` widgets alternating, `image_size` featured_landscape. Lines as `<div data-paragraph="true">`, empty lines with a NON-BREAKING space (a normal space collapses).
- Leave out: Flodesk logo/banner, greeting, social links, offer cards, signature name, footer. End with the closing line (e.g. "Herzlichst und Pura Vida").
- Get the full text by downloading the web view with curl and running `tools/newsletter/flodesk_extract.py` in the repo (the web-fetch tool only returns short quotes).
- AltText.ai writes German alt texts on upload: check them for "ss" instead of the sharp s.
- Publishing: set status publish, then purge the page cache through xCloud (Nginx Helper's purge fails here) and check home and /blog/ as a guest (`curl -sI`, x-cache).
- Keep the author's wording; fix only broken markup and proper names, and report typos.

**Why:** Stefan said on 2026-11-23 that newsletters regularly need to be published as posts "like the previous ones".
**How to apply:** full procedure in the repo, `docs/newsletter-posts.md`. Leave the post as a draft for review unless told to publish.
