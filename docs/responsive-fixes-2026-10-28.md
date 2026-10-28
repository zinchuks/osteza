# OSTEZA: responsive fixes — 28.10.2026

## Scope and deployment state

Six theme files changed locally, plus a read-only regression test. Prices, service descriptions, H1 copy, robots and legal-page states were not changed. The local base is `95072cc`, matching the current Hostinger deployment shown in hPanel. Hostinger auto-deploy from GitHub main is enabled. No commit/push/redeploy was performed: the user's AGENTS.md rule requires explicit confirmation before every git push.

## Changes by requested item

1. **Fixed controls:** `assets/css/main.css`, `assets/js/main.js`. Mobile footer reserves the measured dock height plus 28 px and safe-area inset; ResizeObserver updates the height for wrapping labels. Replaced the old fixed spacer with footer padding. Mobile teaser and back-to-top are hidden. Tablet/desktop footer receives 120 px clearance after measurements showed floating shortcuts overlapping copyright. Teaser is suppressed while the footer is visible. Focus/anchor scroll padding accounts for the mobile dock.
2. **Breadcrumbs:** `inc/blocks.php`, `blocks/service-hero.php`, CSS. Current label has a truncatable span and full title tooltip; service breadcrumbs use one flex row, attached separators and ellipsis. A category equal to the service name is omitted. Eyebrow uses localized Servicios/Services/etc., preserving H1 and service copy.
3. **Contact social buttons:** CSS. On mobile the label, Instagram and Facebook stack consistently with one gap and left alignment. Tablet/desktop retain the existing flex row; both buttons stay on the same row in the tested pages.
4. **Blog cover text:** `inc/blog.php`. Preserve an actual space at each intentional br boundary. Text-only extraction now reads “Antes de la obra”, “Tu mudanza”, “Cocina de IKEA”, etc.; visual line breaks are preserved. No post content/cover metadata was rewritten.
5. **Titles:** `inc/seo.php`. Default site suffix is OSTEZA independently of the WordPress site-name setting. Explicit SEO titles normalize the OSTEZA/osteza.es/Hostinger suffix without duplicate branding. Area title drops its final period only; H1 stays byte-equivalent after whitespace normalization. Legal pages remain drafts and were not enabled for title testing.
6. **Dropdown:** no further change needed. Existing shared catalogue is used everywhere. All tested forms have the same 26 options (blank choice + 24 services + other job), in the same order per language.

## Verification

- 75 browser visits: home, moving service, Calpe area, contact and blog × 5 languages × 390/768/1280 px. Viewport height 900 px throughout.
- Each service breadcrumb is one 32 px row; the current label supports ellipsis and its separator remains attached.
- Every viewport check reached the actual page end for footer measurements. Copyright and positioned form CTAs do not intersect fixed controls. Forms were not submitted, no chat messages were sent.
- All 75 records report `scrollWidth == documentElement.clientWidth`. Chrome reserves a 15 px vertical scrollbar: for nominal widths 390/768/1280 the usable widths are 375/753/1265. This is not horizontal overflow; the requested window widths were separately verified against innerWidth.
- Raw HTML fetched for all 25 URLs: each title ends with `| OSTEZA`, area titles do not end with a period before the suffix, and every H1 matches the pre-change baseline. This covers view-source checks in every language.
- PHP 8.3: 12 read-only text/title regression checks passed. JS syntax and git diff --check passed.
- Full HTTP/content regressions: 265 public pages, 300 local linked URLs, 15 legal pages still hidden, local robots unchanged, zero reported errors.
- Browser console error log checked at the end: empty. Occasional browser-tool navigation timeouts were resolved by inspecting the actually loaded page; they are not recorded as successful visits until the page and measurements were confirmed.

## Measurements by language and width

Each cell covers five page types. Clearance is the **minimum** vertical gap from copyright to the nearest fixed control across those pages, in px. No horizontal overflow, footer overlap or CTA overlap was found in any cell.

| Language | 390 px: visits / clearance | 768 px: visits / clearance | 1280 px: visits / clearance |
|---|---|---|---|
| es | 5/5 · 71.9 px | 5/5 · 27.6 px | 5/5 · 27.9 px |
| en | 5/5 · 59.6 px | 5/5 · 28.0 px | 5/5 · 28.0 px |
| uk | 5/5 · 71.6 px | 5/5 · 27.8 px | 5/5 · 27.9 px |
| ru | 5/5 · 72.1 px | 5/5 · 28.0 px | 5/5 · 28.0 px |
| pl | 5/5 · 71.6 px | 5/5 · 27.8 px | 5/5 · 27.8 px |

## H1 punctuation preserved and flagged

- es: `Montaje de cocinas y muebles en Calpe.`. Only the document title lost the period.
- en: `Kitchen and furniture assembly in Calpe.`. Only the document title lost the period.
- uk: `Збирання кухонь і меблів у Кальпе.`. Only the document title lost the period.
- ru: `Сборка кухонь и мебели в Кальпе.`. Only the document title lost the period.
- pl: `Montaż kuchni i mebli w Calpe.`. Only the document title lost the period.

## Actual tested URLs

| Language | Page type | URL |
|---|---|---|
| es | home | `http://localhost:8080/` |
| es | service | `http://localhost:8080/servicios/mudanzas-y-transporte/` |
| es | area | `http://localhost:8080/zonas/calpe/` |
| es | contact | `http://localhost:8080/contacto/` |
| es | blog | `http://localhost:8080/blog/` |
| en | home | `http://localhost:8080/en/` |
| en | service | `http://localhost:8080/en/services/moving-and-transport/` |
| en | area | `http://localhost:8080/en/areas/calpe/` |
| en | contact | `http://localhost:8080/en/contact/` |
| en | blog | `http://localhost:8080/en/blog-en/` |
| uk | home | `http://localhost:8080/uk/` |
| uk | service | `http://localhost:8080/uk/posluhy/pereizdy-i-perevezennia/` |
| uk | area | `http://localhost:8080/uk/zony/kalpe/` |
| uk | contact | `http://localhost:8080/uk/kontakty/` |
| uk | blog | `http://localhost:8080/uk/bloh/` |
| ru | home | `http://localhost:8080/ru/` |
| ru | service | `http://localhost:8080/ru/uslugi/pereezdy-i-perevozki/` |
| ru | area | `http://localhost:8080/ru/goroda/kalpe/` |
| ru | contact | `http://localhost:8080/ru/sviazatsia/` |
| ru | blog | `http://localhost:8080/ru/blog-ru/` |
| pl | home | `http://localhost:8080/pl/` |
| pl | service | `http://localhost:8080/pl/oferta/przeprowadzki-i-transport/` |
| pl | area | `http://localhost:8080/pl/obszary/calpe/` |
| pl | contact | `http://localhost:8080/pl/kontakt/` |
| pl | blog | `http://localhost:8080/pl/blog-pl/` |

## Screenshots and raw evidence

- es: [390 px service](/private/tmp/osteza-responsive-qa/es-390-service.jpg) · [768 px service](/private/tmp/osteza-responsive-qa/es-768-service.jpg) · [1280 px service](/private/tmp/osteza-responsive-qa/es-1280-service.jpg)
- en: [390 px service](/private/tmp/osteza-responsive-qa/en-390-service.jpg) · [768 px service](/private/tmp/osteza-responsive-qa/en-768-service.jpg) · [1280 px service](/private/tmp/osteza-responsive-qa/en-1280-service.jpg)
- uk: [390 px service](/private/tmp/osteza-responsive-qa/uk-390-service.jpg) · [768 px service](/private/tmp/osteza-responsive-qa/uk-768-service.jpg) · [1280 px service](/private/tmp/osteza-responsive-qa/uk-1280-service.jpg)
- ru: [390 px service](/private/tmp/osteza-responsive-qa/ru-390-service.jpg) · [768 px service](/private/tmp/osteza-responsive-qa/ru-768-service.jpg) · [1280 px service](/private/tmp/osteza-responsive-qa/ru-1280-service.jpg)
- pl: [390 px service](/private/tmp/osteza-responsive-qa/pl-390-service.jpg) · [768 px service](/private/tmp/osteza-responsive-qa/pl-768-service.jpg) · [1280 px service](/private/tmp/osteza-responsive-qa/pl-1280-service.jpg)

- [EN mobile footer](/private/tmp/osteza-responsive-qa/en-390-footer.jpg)
- [All 75 measurements](/private/tmp/osteza-responsive-qa/final-measurements.json)
- [25 raw HTML title/H1 checks](/private/tmp/osteza-responsive-qa/source-verification.json)

## Changed files

- `wp-content/themes/osteza/assets/css/main.css`
- `wp-content/themes/osteza/assets/js/main.js`
- `wp-content/themes/osteza/blocks/service-hero.php`
- `wp-content/themes/osteza/inc/blocks.php`
- `wp-content/themes/osteza/inc/blog.php`
- `wp-content/themes/osteza/inc/seo.php`
- `tests/responsive-content.php`
- This report.

## Remaining work

Local implementation and verification are complete. Production has not received these changes. To apply via the configured Hostinger workflow, obtain explicit user confirmation for commit/push, then verify the auto-deployment and live pages. No robots or legal-page settings need to change.
