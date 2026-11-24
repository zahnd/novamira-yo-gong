# Working through the Novamira MCP

MCP server **`novamira-yo-gong-com`** (OAuth via claude.ai), tools `mcp-adapter-discover-abilities`,
`mcp-adapter-get-ability-info`, `mcp-adapter-execute-ability`. The connection runs as **WP user 1
`webmaster`** (administrator). Novamira 1.12.16, Novamira Pro 1.14.0.

`discover-abilities` returned **172 abilities** (about 110 KB) on 2026-11-23. Save it and filter it with
`jq`, don't read it whole.

## Safety rules (from Novamira)

- Never update, deactivate or delete the Novamira plugins.
- Never change user 1's credentials, OAuth connections or application passwords.
- Use WordPress-native and plugin-native mechanisms: JetEngine owns the content model, Elementor owns the layout.
- Before restructuring a page, the build approach is **Elementor** (asked once; answer for this site).

## Traps

- **Overlapping Rank Math ability groups.** Novamira Pro's `novamira/rank-math-*` and Rank Math's own
  `rank-math/*` are both enabled, and Novamira suggests keeping one. Stefan decided to keep both
  (`bergwerk/wordpress → novamira/Keep both Rank Math ability groups`). Pick the better ability per task, and don't raise it again.
- **WP fail2ban** is active: don't request `/?author=N` or `/wp-json/wp/v2/users` from the server itself.
  See `bergwerk/wordpress → debugging/Server-side enumeration probes get the server banned by fail2ban`.
- If the tools don't appear in Claude Code: `bergwerk/wordpress → novamira/Novamira tools missing in Claude Code after connect`.

## Novamira skills worth loading (`novamira/skill-get`)

`elementor-build-page`, `novamira-design`, `jetengine-integration`, `jetelements-elementor`,
`element-pack-elementor`, `dynamic-data-binding`, `fluentforms-integration`, `rank-math-integration`.

## Useful abilities

| Task | Ability |
|---|---|
| Arbitrary read or query | `novamira/execute-php` (`{code}`; `return` a value) |
| WP-CLI | `novamira/run-wp-cli` (+ `get-wp-cli-job`) |
| Files | `novamira/read-file`, `write-file`, `edit-file`, `list-directory` |
| Elementor | `elementor-get-content`, `-set-content`, `-add/-edit/-delete-element`, `-list-templates`, `-get/-set-template-conditions`, `-list-v3-styles`, `-clear-document-cache` |
| JetEngine | `jetengine-list/get/create/edit-post-type`, `-meta-box`, `-options-page` (queries and listings: execute-php, see the skill) |
| Fluent Forms | `fluentforms-list-forms`, `-get-form-canonical`, `-list-notifications`, `-list-submissions` |
| SEO | `novamira/rank-math-get/edit-post-seo`, `rank-math/get-redirections`, `rank-math/get-404-logs` |
| Browser login | `novamira/create-admin-access-link` |
| Memory store | `novamira/memory-list`, `memory-get`, `memory-save` (pass `id` to update), `memory-delete` |

## The site's memory store

The store is the site brief (`bergwerk/wordpress → novamira/Novamira memory store is the site brief`).
It was empty until 2026-11-23, then seeded:

| ID | Name | Type |
|---|---|---|
| 2294 | Where the docs live | reference |
| 2295 | Site rules | project |
| 2296 | Traps on yo-gong.com | project |
| 2297 | Session log | project (one line per session) |
| 2307 | Newsletter to blog post | project (added 2026-11-23) |

Update them by `id` and don't create duplicates. Verbatim mirror: [novamira-memories/](novamira-memories/README.md).
