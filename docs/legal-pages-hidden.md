# Приховування юридичних сторінок — 28.10.2026

**Обсяг: локальні зміни та read-only перевірка live osteza.es. На live нічого не змінювалося: потрібний стан уже був встановлений.** Попередні звіти про опубліковані юридичні заготовки є історичними: актуальний стан усіх 15 сторінок — Draft.

## Результат

- Змінено лише статус legal pages на `draft`; ID, slug, контент, placeholder, переклади та метадані збережено. SHA-256 тексту до/після однаковий для кожної сторінки.
- `footer.php` не створює порожній nav Legal. Наявні `osteza_legal_pages()`, `osteza_link()` та `osteza_privacy_url()` перевіряють publish status; тому динамічні footer/privacy links та посилання з блоків зникли без видалення збереженого контенту.
- Перевірено всі 265 публічних сторінок/статей: жодного посилання на приховані legal URLs у меню, футері, контактних повідомленнях або body. Окремого локального cookie-banner плагіна, який додавав би посилання, немає.
- WordPress sitemap provider не повертає legal URLs. Draft pages виключені зі стандартного публічного пошуку; контрольна перевірка повернула 0 legal IDs. Глобальна індексація локального середовища не змінювалася.
- Запити до кожної адреси та її `?page_id=ID` без cookies/авторизації: HTTP 404, placeholder відсутній у відповіді (30 перевірок).
- Звичайний браузер підтвердив WordPress logged-in=false та Page not found замість юридичного вмісту. Incognito-вікно недоступне через підключений browser API; не стверджуємо, що перевірка виконана в incognito. Перевірка анонімного HTTP-доступу виконана окремо для кожного URL.
- `python3 tests/public-content.py`: PASS, 265 публічних сторінок, 300 linked local URL без помилок, 15 hidden pages, 0 public legal pages/links. PHP lint та git diff --check: PASS.

## Сторінки

| Мова | URL | ID | Спосіб приховування | Вміст |
|---|---|---:|---|---|
| es | `/aviso-legal/` | 75 | Draft; анонімний HTTP 404 | Без змін |
| es | `/politica-de-cookies/` | 76 | Draft; анонімний HTTP 404 | Без змін |
| es | `/politica-de-privacidad/` | 3 | Draft; анонімний HTTP 404 | Без змін |
| en | `/en/cookie-policy/` | 183 | Draft; анонімний HTTP 404 | Без змін |
| en | `/en/legal-notice/` | 179 | Draft; анонімний HTTP 404 | Без змін |
| en | `/en/privacy-policy/` | 187 | Draft; анонімний HTTP 404 | Без змін |
| uk | `/uk/polityka-konfidentsiinosti/` | 188 | Draft; анонімний HTTP 404 | Без змін |
| uk | `/uk/polityka-shchodo-failiv-cookie/` | 184 | Draft; анонімний HTTP 404 | Без змін |
| uk | `/uk/pravova-informatsiia/` | 180 | Draft; анонімний HTTP 404 | Без змін |
| ru | `/ru/politika-konfidentsialnosti/` | 189 | Draft; анонімний HTTP 404 | Без змін |
| ru | `/ru/politika-v-otnoshenii-cookie/` | 185 | Draft; анонімний HTTP 404 | Без змін |
| ru | `/ru/pravovaia-informatsiia/` | 181 | Draft; анонімний HTTP 404 | Без змін |
| pl | `/pl/nota-prawna/` | 182 | Draft; анонімний HTTP 404 | Без змін |
| pl | `/pl/polityka-cookies/` | 186 | Draft; анонімний HTTP 404 | Без змін |
| pl | `/pl/polityka-prywatnosci/` | 190 | Draft; анонімний HTTP 404 | Без змін |

## Змінені файли

- `wp-content/themes/osteza/footer.php`: умовний вивід nav Legal тільки за наявності опублікованих сторінок.
- `scripts/hide-legal-pages.php`: CLI-only, обмежений localhost; без --apply лише інспектує. З --apply переводить наявні 15 сторінок у Draft та перевіряє незмінність контенту. Перелік перекладів отримує від Polylang.
- `tests/public-content.py`: перевіряє Draft, відсутність legal links/content і анонімні 404 замість попереднього очікування публічних placeholders.

У локальній копії не залишилося сторінок, які не вдалося приховати. На live WordPress окремо перевірено всі ті самі 15 ID: вони вже мають статус «Чернетка», тому повторно не змінювалися. Усі 15 HTTPS URL повертають 404 без cookies, у п’яти головних немає legal links, блок Legal порожній (це дозволений запитом стан), пошук не повертає legal links, обидва sitemap endpoints повертають 404. Переносити локальні статуси на live повторно не потрібно. Попередні migration ZIP/SQL містять старі стани й не оновлювалися. Legal pages не слід повторно публікувати до дозволеного запуску; попередній maintenance-script для створення публічних placeholders не запускати повторно.

## Read-only live перевірка

Адміністративна сесія osteza.es виявилася доступною. Статуси перевірено через список сторінок, не відкриваючи редакторів і не змінюючи контенту. Окремі HTTP-запити виконані без cookies та авторизації; заголовки/тексти приватних сторінок не виводилися. Incognito недоступне browser API; анонімність HTTP-перевірки не залежить від відкритої адміністративної сесії.

Підсумковий live crawl: 265 публічних сторінок/статей усіма мовами, 0 помилок HTTP, 0 сторінок із legal links. На live не виконувалося жодних записів/оновлень; потрібний стан уже був наявний. Локальна копія синхронізована за статусами, вміст не змінений.
