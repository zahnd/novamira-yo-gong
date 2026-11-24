# CLAUDE.md: www.yo-gong.com

Documentation repo for **www.yo-gong.com** ("Yo-Gong": yoga, gong/sound and energy work, with retreats,
events, energy coaching, a yoga teacher training, a podcast and a blog). There is **no application code
here**. The site is a live WordPress install that you work on **remotely through the Novamira MCP
`novamira-yo-gong-com`** (`mcp-adapter-discover-abilities` / `-get-ability-info` / `-execute-ability`).
This repo holds the docs, a byte-exact mirror of the site's WPCode snippets and Novamira memories, and
the tool to re-sync them.

## Key facts

| | |
|---|---|
| CMS | WordPress 7.1.5, PHP 8.4, locale `de_CH`, timezone Europe/Zurich. **Production, no staging** |
| Theme / builder | Hello Elementor (no child theme) + **Elementor / Elementor Pro 4.4.1** (v3 widgets, Theme Builder), Element Pack Pro, JetElements, JetTricks |
| Content model | **JetEngine**: CPTs `event`, `person`, `jet-cpt-sessions`, `jet-cpt-location`, `newsletter`; meta boxes on retreat posts, podcast episodes (`sr_playlist`, Sonaar) and pages; 15 queries, 10 listings |
| Forms / mail | Fluent Forms 3 (contact) and 4 (newsletter, forwarded to **Flodesk** by WPCode 1641), both in the footer. FluentSMTP → Postmark |
| Languages | DE (default) + EN machine translation through **Linguise** |
| Hosting / cache | **xCloud**, team Sun Chasing Web Design, server SCWD Prod 1. Nginx FastCGI page cache: **Nginx Helper can't purge it**, so purge through xCloud after publishing ([docs/tech-stack.md](docs/tech-stack.md)) |
| Other | Rank Math SEO + PRO, Complianz premium (EU), AltText.ai, WP fail2ban |
| Custom code | WPCode Lite (4 snippets, 2 active) + Elementor Kit custom CSS. Nothing else |
| MCP user | WP user 1 `webmaster` |

## Rules

1. **Never touch the Novamira plugins** or WP user 1: no changes to its credentials, OAuth connections or application passwords.
2. **It's production.** Read before writing, back up before changing, and confirm visible or destructive changes with Stefan first.
3. **Elementor is the builder.** Load the Novamira skills (`novamira/skill-get`) `elementor-build-page` and `novamira-design` before building. Use the Kit 7 globals, not hard-coded colours or fonts ([docs/elementor.md](docs/elementor.md)).
4. **JetEngine owns the content model.** Use the `novamira/jetengine-*` abilities, and never register CPTs or fields in PHP.
5. **Custom code only in WPCode**, changed with the safe procedure in [docs/custom-code.md](docs/custom-code.md). Then re-sync `wpcode-snippets/` and commit.
6. **Don't break the newsletter.** WPCode 1641 depends on form 4's field names `names` / `email` ([docs/forms-and-email.md](docs/forms-and-email.md)).
7. After template or CSS changes: `novamira/elementor-clear-document-cache`. After any visible change (publishing too), purge the page cache **through xCloud** (`sites.cache.purge`), because Nginx Helper's purge fails here. Check the delivered page as a guest.
8. Content is German (`de_CH`, "ss", never "ß"). Repo docs are English.

## Knowledge stores: keep all three in step

| Store | What goes there | How |
|---|---|---|
| `docs/` + `wpcode-snippets/` in this repo | Detailed reference and the verbatim code mirror | Edit, re-sync ([tools/sync](tools/sync/README.md)), commit |
| **Novamira memory** on the site | The site brief: rules, traps, session log, pointer to the other stores. IDs 2294–2297 | `novamira/memory-list` / `-get` / `-save` (pass `id` to update; no duplicates). Mirrored in `docs/novamira-memories/` |
| **Basic Memory** project `yo-gong` | One note per `docs/` file in folder `Website/`, hub note `Website/How this project works` | `bm_read` (take the checksum), then `bm_edit` / `bm_write` with `project: "yo-gong"` |

- **At the start of work:** read the Novamira memory store, and search Basic Memory `yo-gong` together with
  **`wordpress`** (`bm_search` with `projects: ["yo-gong", "wordpress"]`). The `wordpress` project holds general,
  site-independent plugin know-how. Read the folder for the plugin you touch first: `elementor/` (esp.
  `Elementor over MCP`), `jetengine/`, `wpcode/`, `fluent-forms/`, `fluent-smtp/`, `linguise/`, `complianz/`,
  `rankmath/`, `alttext-ai/`, `novamira/`, `debugging/`, `core/`.
- **At the end of work:** add a line to the Novamira "Session log" (2297) and update any affected memory. Then
  update the matching `docs/` file and its Basic Memory note, re-sync the mirrors, and commit.
- A finding that would be true on any site goes to Basic Memory `wordpress`, not here. Reference it in full as text
  (`bergwerk/wordpress → <folder>/<note>`), because `[[wikilinks]]` don't cross projects.
- Don't edit the generated `index.md` and `log.md` in Basic Memory.

## Docs map

| File | What's in it |
|---|---|
| [docs/site-overview.md](docs/site-overview.md) | What the site is, pages with IDs and templates, posts and categories, other content types, menus, where the forms sit |
| [docs/tech-stack.md](docs/tech-stack.md) | Server, cache, cron, users, all plugins with versions and purpose, Linguise setup |
| [docs/content-model.md](docs/content-model.md) | JetEngine CPTs and fields, meta boxes, Query Builder queries, listings |
| [docs/elementor.md](docs/elementor.md) | Theme Builder templates and conditions, Kit colours and typography (Adobe Fonts), Kit custom CSS |
| [docs/forms-and-email.md](docs/forms-and-email.md) | Fluent Forms 3 and 4, the Flodesk webhook, Turnstile, FluentSMTP/Postmark |
| [docs/custom-code.md](docs/custom-code.md) | WPCode snippets, what depends on them, the safe change procedure |
| [docs/novamira-mcp.md](docs/novamira-mcp.md) | Working through the MCP: safety rules, traps, skills, abilities, memory store IDs |
| [docs/newsletter-posts.md](docs/newsletter-posts.md) | **Recurring job:** turning a Flodesk newsletter into a blog post (structure, what to leave out, procedure, list of posts done) |
| [docs/performance-audit-2026-11.md](docs/performance-audit-2026-11.md) | Image and speed audit 2026-11-24: PageSpeed mobile 52 / LCP 26 s, original-size background images, recommendations R1–R7 |
| [docs/open-items.md](docs/open-items.md) | Findings and cleanup candidates waiting for a decision |
| [docs/novamira-memories/](docs/novamira-memories/README.md) | Verbatim mirror of the site's Novamira memories |
| [wpcode-snippets/](wpcode-snippets/README.md) | Byte-exact mirror of every WPCode snippet, `manifest.json` with `live_sha1` |
| [tools/newsletter/](tools/newsletter/flodesk_extract.py) | Extracts text, links and images from a Flodesk web-view email |
| [tools/sync/](tools/sync/README.md) | How to re-pull snippets and memories from the site into this repo |

_First MCP audit (read-only): 2026-11-23._
