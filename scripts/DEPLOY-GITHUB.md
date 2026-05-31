# استقرار از `rentnaab-github` (GitHub: vilafarda_new)

این پوشه منبع اصلی برای **push به GitHub** و **ارسال به سرور** است.

## جریان کار

### ۱. توسعه و build (همیشه در همین پوشه)

```bash
cd /opt/lampp/htdocs/rentnaab-github
npm run production
```

### ۲. Push به GitHub

```bash
git add -A
git status
git commit -m "پیام commit"
git push github main
```

**توجه:** `repo-git.tar.gz` را commit نکنید (در `.gitignore` است).

### ۳. ارسال به سرور (بدون git pull)

```bash
chmod +x scripts/deploy-banner-video-to-server.sh
./scripts/deploy-banner-video-to-server.sh
```

### ۴. روی سرور

```bash
cd /var/www/vilafarda
php artisan route:clear && php artisan view:clear && php artisan route:cache
ffmpeg -version
```

---

## اگر در `rentnaab` هم تغییر دادید

قبل از build/push، یک‌بار به github کپی کنید:

```bash
/opt/lampp/htdocs/rentnaab/scripts/sync-rentnaab-to-github.sh
```

سپس همه کارها را از `rentnaab-github` ادامه دهید.

---

## ثبت‌نام موبایل (نام و نام خانوادگی)

فایل‌های این قابلیت:

- `app/Http/Controllers/Api/AuthController.php`
- `public/js/mobile-login.js`
- `resources/views/main/auth/login-mobile.blade.php`

**نیازی به `npm run production` نیست** — فقط PHP، Blade و JS استاتیک.

بعد از push، روی سرور:

```bash
cd /var/www/vilafarda
git pull github main
php artisan view:clear
php artisan route:clear
```

---

## فایل‌های ضروری روی سرور برای آپلود ویدئو

- `public/assets/admin/js/admin.js`
- `public/vendor/ffmpeg/ffmpeg-core.js`
- `public/vendor/ffmpeg/ffmpeg-core.wasm`
- `app/Services/IndexBannerVideoEncoder.php`
- route: `admin.setting.index-page.banner-video`

---

## خطای 413 (Request failed with status code 413)

یعنی **حجم درخواست** از سقف **nginx** یا **PHP** بیشتر است (اغلب nginx با `client_max_body_size 1m` یا `2m`).

**روی سرور:**

```bash
php -i | grep -E 'post_max_size|upload_max_filesize'
```

در تنظیمات nginx سایت (داخل `server { }`):

```nginx
client_max_body_size 128M;
```

سپس:

```bash
sudo nginx -t && sudo systemctl reload nginx
```

نمونه: `scripts/nginx-upload-limit.conf.example`
