# Forms and e-mail

_Verified through the Novamira MCP on 2026-11-23. Skill: `fluentforms-integration`. General notes:
`bergwerk/wordpress → fluent-forms/`, `bergwerk/wordpress → fluent-smtp/FluentSMTP sets the sender on every Bergwerk site`._

## Fluent Forms

| ID | Title | Placed in | Fields | Confirmation | Notification | Entries (2026-11-23) |
|---|---|---|---|---|---|---|
| 3 | Contact Form | Footer 183 (all pages) | `names`, `email`, `phone`, `dropdown` (select), `description` (textarea), `gdpr-agreement`, `cf-turnstile-response` | same page, shows section template 913 "Form Thank You" | "Admin Notification Email" **on**, to one @yo-gong.com and one @bluewin.ch address | 50, last 2026-10-29 |
| 4 | Newsletter | Footer 183 and popup 1362 | `names` (input_name, `first_name`), `email`, `gdpr-agreement`, `cf-turnstile-response` (Turnstile) | same page, shows section template 945 | Admin notification **off** | 25, last 2026-07-12 |

### Newsletter → Flodesk

Form 4 has **no Fluent Forms integration**. WPCode snippet **1641** hooks
`fluentform/before_insert_submission` and, when `$form['id'] == 4`, POSTs JSON
`{"first_name": …, "email": …}` with cURL to `https://server.bergwerk.li:9000/hooks/yogong-flodesk` (a webhook
endpoint on a Bergwerk server that passes the contact on to Flodesk). Consequences:

- Renaming the `names` / `email` fields of form 4, or rebuilding the form under a new ID, breaks the Flodesk sync silently.
- The call is synchronous, without a timeout, ignores the response, and disables TLS verification
  (`CURLOPT_SSL_VERIFYPEER` false). If the endpoint is slow, the form submission is slow; if it fails,
  nobody notices. See [open-items.md](open-items.md).
- The relay on `server.bergwerk.li` isn't documented here; the code in [wpcode-snippets/](../wpcode-snippets/README.md) is the only record on this side.

## Spam protection

- Both forms use Fluent Forms' own Turnstile field.
- The Simple Cloudflare Turnstile plugin protects login, registration and password reset; its Fluent
  Forms and Elementor integrations are **off**.

## Mail

**FluentSMTP → Postmark**, one connection, sender `daniela@yo-gong.com`. No other mappings.
