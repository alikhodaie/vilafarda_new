# RentNaab — نسخه برای GitHub

این پوشه کپی پروژه است (پوشه اصلی `rentnaab` دست‌نخورده مانده).

## محتویات

کد منبع، تنظیمات، `public/assets` (به‌جز فایل‌های build شده)، و ساختار `storage` بدون لاگ/کش.

## قبل از push

```bash
cd /opt/lampp/htdocs/rentnaab-github
git init
git add .
git commit -m "Initial commit"
```

فایل `.env` در این کپی نیست — روی سرور از `.env.example` کپی کنید.

## روی سرور (بعد از clone)

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run production
```

فایل‌های حجیم و `.env` را از پوشه هم‌سطح `rentnaab-server-upload` کپی کنید (راهنما در `README-SERVER-UPLOAD.md` آن پوشه).
