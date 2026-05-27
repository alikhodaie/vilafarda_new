# راه‌اندازی Rentnaab با Docker (VPS)

## پیش‌نیاز

- Docker Engine و Docker Compose plugin روی VPS
- دامنه (اختیاری برای SSL)

## ۱. کلون و تنظیم `.env`

```bash
git clone <repo-url> /var/www/rentnaab
cd /var/www/rentnaab
cp .env.example .env
```

مقادیر مهم برای Docker:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
APP_DOMAIN=your-domain.com
APP_PORT=80

DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=rentnaab
DB_USERNAME=rentnaab
DB_PASSWORD=your-strong-password
DB_ROOT_PASSWORD=your-root-password

REDIS_HOST=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# فقط HTTP تا قبل از صدور گواهی SSL
NGINX_SSL=false
```

سپس:

```bash
docker compose -f docker-compose.yml -f docker-compose.prod.yml run --rm app php artisan key:generate
```

## ۲. بالا آوردن سرویس‌ها

```bash
chmod +x docker/deploy.sh docker/entrypoint.sh
./docker/deploy.sh up
```

یا:

```bash
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build
```

## ۳. نصب وابستگی‌ها و مایگریشن

```bash
./docker/deploy.sh install
./docker/deploy.sh artisan migrate --force
```

اگر `public/storage` لازم است:

```bash
./docker/deploy.sh artisan storage:link --force
```

## ۴. SSL با Certbot (روی خود VPS)

```bash
# ابتدا با NGINX_SSL=false سایت باید روی پورت 80 بالا باشد
sudo apt install certbot
sudo certbot certonly --standalone -d your-domain.com
```

بعد در `.env`:

```env
NGINX_SSL=true
```

و ری‌استارت:

```bash
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d nginx
```

## ۵. توسعه محلی

```bash
docker compose up -d --build
```

- اپ: `http://localhost` (یا پورت `APP_PORT`)
- MariaDB: پورت `DB_FORWARD_PORT` (پیش‌فرض 3306)

## سرویس‌ها

| سرویس | نقش |
|--------|-----|
| `app` | PHP-FPM (Laravel) |
| `nginx` | وب‌سرور |
| `mariadb` | دیتابیس |
| `redis` | کش / سشن (در صورت تنظیم در `.env`) |
| `scheduler` | `artisan schedule:run` هر دقیقه |

## دستورات پرکاربرد

```bash
./docker/deploy.sh logs
./docker/deploy.sh artisan cache:clear
./docker/deploy.sh down
```

## ایمپورت دیتابیس موجود

```bash
docker compose -f docker-compose.yml -f docker-compose.prod.yml exec -T mariadb \
  mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" < backup.sql
```

## نکات

- پوشه‌های `storage` و `bootstrap/cache` باید قابل نوشتن باشند؛ entrypoint سطح دسترسی را تنظیم می‌کند.
- برای مایگریشن خودکار هنگام استارت: `RUN_MIGRATIONS=true` در `.env` (پیشنهاد: فقط در deploy اول).
- فایل `docker-compose.override.yml` برای تنظیمات شخصی شماست (در gitignore).
