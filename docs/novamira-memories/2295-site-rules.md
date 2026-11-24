---
novamira_id: 2295
title: "Site rules"
type: project
description: "How to work on yo-gong.com: production without staging, Elementor + JetEngine own layout and content model, custom code only in WPCode, Kit globals, German de_CH content, cache purge"
created: 2026-11-24 04:03:06
updated: 2026-11-24 13:53:57
---

# Site rules

Rules for working on www.yo-gong.com (Yo-Gong, yoga / sound / retreats; content German de_CH, Swiss spelling "ss" not "ß"; Linguise translates DE -> EN automatically).

- **Production, no staging.** Read before writing, back up before changing, confirm visible or destructive changes with Stefan first.
- **Never touch the Novamira plugins** or WP user 1 `webmaster` (the MCP user): no changes to its credentials, OAuth connections or application passwords.
- **Elementor (+ Pro) on Hello Elementor (no child theme)** is the builder. v3 widgets/containers, Theme Builder templates. Load the Novamira skills `elementor-build-page` and `novamira-design` before visual work. Use Kit 7 globals (colours BG #F7EFDE, accent #B5892C, BG Green #A3B18A; fonts freight-display-pro / futura-pt / lulo-one from Adobe Fonts) instead of hard-coded values.
- **JetEngine owns the content model** (CPTs event, person, newsletter, jet-cpt-sessions, jet-cpt-location; meta boxes Retreats on posts, Episodes on sr_playlist, Pages). Use the `novamira/jetengine-*` abilities, never register CPTs/fields in PHP.
- **Custom code only in WPCode (Lite 2.4.0).** Every snippet is mirrored in the repo; follow the safe write procedure in `bergwerk/wordpress -> wpcode/WPCode Pro` (base64, raw write, sha1, cache rebuild in the same request) and re-sync the repo afterwards.
- After template/CSS changes: `novamira/elementor-clear-document-cache`. After ANY visible change (publishing too): purge the page cache through **xCloud** (`sites.cache.purge`, site uuid 1c37b5e0-2fb2-4e63-a538-f80998fc6c3c, team "Sun Chasing Web Design" 81c0f251-6c0e-4140-b93d-cd157eaf274b). Nginx Helper's own purge fails on this server.
- Every Elementor background image gets an **Image Resolution** (cards featured_landscape, full-width header_1920); never leave it empty.
- Verify on the delivered page as a guest (Chrome), not only in the DB.

**Why:** live client site with a newsletter and contact form in use; the setup was documented 2026-11-23 in the first MCP audit.
**How to apply:** every session on this site.
