---
novamira_id: 2294
title: "Where the docs live"
type: reference
description: "yo-gong.com knowledge is kept in three stores in step: this memory store, the repo novamira-yo-gong (CLAUDE.md + docs/ + wpcode-snippets/), Basic Memory project yo-gong"
created: 2026-11-24 04:03:04
updated: 2026-11-24 04:03:04
---

# Where the docs live

Bergwerk keeps the knowledge about www.yo-gong.com in three places. Keep them in step in the same session.

1. **This Novamira memory store**: the site brief. Rules, non-obvious traps, a session log. Read it first (`novamira/memory-list`), write to it last.
2. **Git repo `novamira-yo-gong`** (GitHub zahnd/novamira-yo-gong): `CLAUDE.md` is the map; `docs/*.md` is the detailed reference (pages, templates, JetEngine model, forms, custom code, open items); `wpcode-snippets/` is a byte-exact mirror of every WPCode snippet with `manifest.json` (`live_sha1` = sha1 of the stored post_content); `docs/novamira-memories/` mirrors this store verbatim. Re-sync with `tools/sync`.
3. **Basic Memory project `yo-gong`**, folder `Website/`: notes that mirror `docs/`, hub note `Website/How this project works`.

General WordPress/plugin know-how (true on any site) is canonical in Basic Memory project `wordpress` (folders elementor/, jetengine/, wpcode/, fluent-forms/, linguise/, complianz/, rankmath/, novamira/ ...). Read the relevant note before working on a plugin; don't copy it here.

**How to apply:** at the start of work read this store and search Basic Memory `yo-gong` + `wordpress`. At the end: add a line to the Session log memory, update any affected memory by id (no duplicates), update the repo docs and the matching Basic Memory note, re-sync the repo mirror and commit.
