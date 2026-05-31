#!/usr/bin/env bash
# کپی تغییرات از rentnaab به rentnaab-github (قبل از build/push/deploy از پوشه github)
set -euo pipefail

SRC="/opt/lampp/htdocs/rentnaab"
DST="/opt/lampp/htdocs/rentnaab-github"

if [[ ! -d "$SRC" || ! -d "$DST" ]]; then
  echo "مسیر SRC یا DST پیدا نشد."
  exit 1
fi

if ! touch "$DST/.write-test" 2>/dev/null; then
  echo "خطا: روی $DST دسترسی نوشتن ندارید."
  echo "  sudo chown -R \"\$(whoami):\$(whoami)\" \"$DST\""
  exit 1
fi
rm -f "$DST/.write-test"

FILES=(
  app/Helpers/helpers.php
  app/Http/Controllers/Admin/SettingController.php
  app/Http/Controllers/Api/AuthController.php
  app/Services/IndexBannerVideoEncoder.php
  resources/views/admin/setting/pages/index-page.blade.php
  resources/views/main/auth/login-mobile.blade.php
  resources/js/components/admin/IndexBannerVideoUpload.vue
  resources/js/src/compressUploadBannerVideo.js
  public/js/mobile-login.js
  routes/web.php
  webpack.mix.js
  package.json
  package-lock.json
  public/assets/css/styles.css
  public/.htaccess
  config/php-upload-limits.ini
  scripts/nginx-upload-limit.conf.example
  scripts/sync-rentnaab-to-github.sh
)

# deploy و DEPLOY-GITHUB فقط در rentnaab-github نگه داشته می‌شوند (اسکریپت deploy در rentnaab فقط راهنماست).

echo "==> کپی از $SRC به $DST"
for f in "${FILES[@]}"; do
  if [[ -f "$SRC/$f" ]]; then
    mkdir -p "$DST/$(dirname "$f")"
    cp -f "$SRC/$f" "$DST/$f"
    echo "  $f"
  fi
done

echo ""
echo "==> انجام شد. حالا:"
echo "  cd $DST && npm run production"
echo "  git add -A && git commit && git push github main"
echo "  ./scripts/deploy-banner-video-to-server.sh"
