# Tech stack

_Verified through the Novamira MCP on 2026-11-23._

## Platform

| | |
|---|---|
| WordPress | 7.1.5, PHP 8.4.28, locale `de_CH`, timezone Europe/Zurich |
| Server | nginx 1.31.9, hostname `scwd-prod-1`, path `/var/www/www.yo-gong.com/` (path layout as on xCloud; hosting provider not confirmed via the xCloud MCP yet) |
| DB prefix | `wfzp_` |
| Environment | **Production only, no staging** |
| Theme | Hello Elementor (parent), **no child theme**. No mu-plugins |
| Page cache | Nginx FastCGI cache, purged by **Nginx Helper** (purge on edit, `unlink_files`) |
| Cron | `DISABLE_WP_CRON` = true; a server cron runs wp-cron (no overdue events on 2026-11-23) |
| Search engines | Indexing allowed (`blog_public` = 1); sitemap `https://www.yo-gong.com/sitemap_index.xml` (Rank Math) |
| Admin e-mail | on the domain sunchasingtravelers.com (the same owner also has a site reachable as MCP `novamira-sunchasingtravel`) |

## Users

Three administrators: `webmaster` (ID 1, **the MCP user: never change its credentials**), `lea` (ID 2),
`stefanzahnd` (ID 3).

## Plugins

| Plugin | Version | Active | Use on this site |
|---|---|---|---|
| Elementor / Elementor Pro | 4.4.1 | yes | Builder, Theme Builder, popups, loop items |
| Element Pack Pro (BdThemes) | 10.0.3 | yes | Extra widgets (`bdt-*`) |
| JetEngine | 3.9.1.2 | yes | CPTs, meta boxes, queries, listings ([content-model.md](content-model.md)) |
| JetElements | 2.9.2.8 | yes | Extra widgets (`jet-*`) |
| JetTricks | 2.0.2 | yes | Elementor effects/tricks |
| MP3 Audio Player by Sonaar + Pro | 5.16 / 5.9.5 | yes | Podcast episodes (`sr_playlist`) and player |
| Fluent Forms + Pro | 6.2.17 | yes | Forms 3 (contact) and 4 (newsletter) |
| FluentSMTP | 2.4.1 | yes | Mail via Postmark |
| Simple CAPTCHA with Cloudflare Turnstile | 1.44.6 | yes | Turnstile on login, registration and password reset; forms 3 and 4 use Fluent Forms' own Turnstile field |
| Linguise | 2.2.71 | yes | DE (default) → EN machine translation, popup switcher, hreflang alternate links |
| Complianz Privacy Suite premium | 7.6.7 | yes | Cookie banner, region EU; documents on pages 1588 / 1589 |
| Rank Math SEO + PRO | 1.0.280 / 3.0.124 | yes | SEO, sitemap |
| AltText.ai | 1.10.46 | yes | Alt texts (see `bergwerk/wordpress → alttext-ai/AltText.ai on Swiss sites`) |
| WPCode Lite | 2.4.0 | yes | Custom code ([custom-code.md](custom-code.md)) |
| Image Regenerate & Select Crop | 8.2.2 | yes | Image sizes and crops |
| Duplicate Page | 4.5.9 | yes | Editorial helper |
| Temporary Login Without Password | 1.9.10 | yes | Temporary admin logins |
| WP fail2ban | 5.5.0 | yes | Login/enumeration protection (trap: see [novamira-mcp.md](novamira-mcp.md)) |
| Nginx Helper | 2.4.1 | yes | Purges the FastCGI cache |
| Novamira / Novamira Pro | 1.12.16 / 1.14.0 | yes | MCP connection. **Never update, deactivate or delete** |
| Cloudinary | 3.3.8 | **no** | Installed but inactive (cleanup candidate, see [open-items.md](open-items.md)) |

## Languages

Linguise: default `de`, enabled `en` + `de`, switcher as popup (`flag_display_type` popup), placed
manually (`display_position` no), hreflang alternate links on. The popup layout is overridden in the
Elementor Kit custom CSS ([elementor.md](elementor.md)). General Linguise traps:
`bergwerk/wordpress → linguise/`.
