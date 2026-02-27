# Hostinger Self-Hosting Checklist (Laravel + Vue ERP)

## 1. Hosting Plan & Runtime
- Use Hostinger **VPS** (recommended) or Business shared hosting with SSH support.
- Ensure PHP version is compatible with your Laravel version.
- Enable required PHP extensions: `mbstring`, `openssl`, `pdo`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`.

## 2. Server Setup
- Point domain/subdomain to Laravel `public/` directory.
- Set file permissions:
  - `storage/` and `bootstrap/cache/` writable by web user.
- Configure SSL certificate and enforce HTTPS.

## 3. Environment Configuration
Create `.env` using production-safe settings:

```dotenv
APP_NAME="Export ERP"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_db
DB_USERNAME=erp_user
DB_PASSWORD=strong-password

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

CACHE_STORE=database
QUEUE_CONNECTION=database

SANCTUM_STATEFUL_DOMAINS=your-domain.com
```

## 4. Deployment Commands
Run these on Hostinger SSH after code upload:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 5. Scheduler & Queue
- Add cron for scheduler:

```bash
* * * * * php /home/username/path-to-project/artisan schedule:run >> /dev/null 2>&1
```

- If using queue workers, configure Supervisor (VPS) or Hostinger background worker equivalent.

## 6. Security Hardening
- Disable debug in production (`APP_DEBUG=false`).
- Rotate default seeded admin password immediately.
- Restrict admin routes by permission (`dashboard.view`, `user.*`, `role.*`).
- Use HTTPS-only cookies.
- Add regular backup policy for DB + uploaded assets.

## 7. Post-Deploy Verification
- Login with seeded admin account.
- Verify User CRUD: add/edit/delete users.
- Verify Role CRUD and permission matrix updates.
- Verify sidebar and dashboard visibility changes when permissions are changed.
