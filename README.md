# Domain Monitoring Admin

Адмін-панель для автоматичного моніторингу доступності доменів.

Проєкт реалізований як тестове завдання на `Laravel 12` з `Blade` UI та чергами/планувальником для фонових перевірок.

## Основний функціонал

- Авторизація: реєстрація, вхід/вихід, захист приватних сторінок.
- CRUD доменів: створення, редагування, видалення, список доменів користувача.
- Налаштування перевірки для кожного домену:
  - інтервал (`check_interval`, у секундах),
  - таймаут (`timeout`, у секундах),
  - HTTP метод (`GET` або `HEAD`).
- Автоматичні перевірки доменів за розкладом.
- Історія перевірок (логи): дата, результат, код відповіді, час відповіді, помилка.

## Технології

- Backend: `Laravel 12`
- Frontend: `Blade` + `Vite`
- DB: `MySQL 8+` (також підтримується `PostgreSQL 14+` через env)
- Queue/Scheduler: Laravel Queue + Scheduler

## Архітектура (коротко)

- `Domain` - сутність домену та налаштувань перевірки.
- `DomainCheck` - історія перевірок по домену.
- `DomainCheckService` - виконує HTTP-перевірку і записує результат.
- `RunDomainCheckJob` - фонове виконання однієї перевірки.
- `domains:check-due` - команда, що знаходить домени, які пора перевіряти, і диспатчить jobs.
- Scheduler щохвилини запускає `domains:check-due`.

## Структура ключових файлів

- Роути:
  - `routes/web.php`
  - `routes/auth.php`
  - `routes/console.php`
- Домени:
  - `app/Http/Controllers/Domain/DomainController.php`
  - `app/Http/Requests/Domain/StoreDomainRequest.php`
  - `app/Http/Requests/Domain/UpdateDomainRequest.php`
  - `app/Policies/DomainPolicy.php`
  - `resources/views/domains/*`
- Моніторинг:
  - `app/Services/Domain/DomainCheckService.php`
  - `app/Jobs/RunDomainCheckJob.php`
  - `app/Console/Commands/CheckDueDomainsCommand.php`
- Міграції:
  - `database/migrations/*create_domains_table.php`
  - `database/migrations/*create_domain_checks_table.php`

## Вимоги до локального запуску

- PHP `8.4+`
- Composer `2+`
- Node.js `20+` (рекомендовано для актуального Vite)
- MySQL `8+` або PostgreSQL `14+`

## Локальний запуск

1. Встановити залежності:

```bash
composer install
npm install
```

2. Підготувати env:

```bash
cp .env.example .env
php artisan key:generate
```

3. Налаштувати БД у `.env` (приклад для MySQL):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=domain_monitoring_admin
DB_USERNAME=root
DB_PASSWORD=
```

4. Створити БД `domain_monitoring_admin` у вашому MySQL/Postgres.

5. Прогнати міграції:

```bash
php artisan migrate
```

6. Зібрати фронтенд:

```bash
npm run build
```

7. Запустити Laravel:

```bash
php artisan serve --port=8001
```

Відкрити: `http://127.0.0.1:8001`

## Docker запуск

### 1) Підготувати env для Docker

Скопіюйте `.env.example` у `.env` і виставте параметри БД під docker-compose:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=domain_monitoring_admin
DB_USERNAME=app
DB_PASSWORD=app
```

### 2) Підняти контейнери

```bash
docker compose up -d --build
```

### 3) Встановити PHP-залежності всередині контейнера

```bash
docker compose exec app composer install
```

### 4) Згенерувати ключ і прогнати міграції

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

### 5) Відкрити застосунок

`http://127.0.0.1:8001`

Примітки:
- `queue` і `scheduler` запускаються окремими контейнерами автоматично.
- Для зупинки:

```bash
docker compose down
```

## Автоматичні перевірки (важливо)

Для локального авто-моніторингу потрібно запустити 2 процеси:

1. Queue worker:

```bash
php artisan queue:work
```

2. Scheduler:

```bash
php artisan schedule:work
```

Ручний запуск перевірок:

```bash
php artisan domains:check-due
```

## Основні сторінки UI

- `/dashboard` - дашборд
- `/domains` - список доменів
- `/domains/create` - створення домену
- `/domains/{domain}/edit` - редагування
- `/domains/{domain}/checks` - історія перевірок

## Безпека і доступ

- Доступ до доменів ізоляційний: користувач бачить і редагує тільки свої записи.
- Авторизація дій з доменами реалізована через `DomainPolicy`.

## Статус по ТЗ

- Обов'язковий функціонал реалізовано:
  - auth,
  - домени + налаштування перевірок,
  - автоматичні перевірки,
  - логи історії.
- Плюси (опційно): Docker, email-сповіщення та публічний demo deploy - можуть бути додані окремим етапом.

## Demo

- Demo URL: `TBD`
- Deploy target: `Render / Railway / Fly.io`

## Git

Проєкт ведеться в Git-репозиторії. Рекомендований workflow:

- `feature/*` гілки для задач,
- Pull Request на `main`,
- код-рев'ю перед merge.
