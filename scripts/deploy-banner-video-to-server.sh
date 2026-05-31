#!/usr/bin/env bash
# آپلود بنر ویدئو از همین ریپو (rentnaab-github) به سرور — بعد از npm run production
set -euo pipefail

SRC="/opt/lampp/htdocs/rentnaab-github"
REMOTE="${DEPLOY_REMOTE:-root@158.255.74.248:/var/www/vilafarda}"

if [[ ! -d "$SRC" ]]; then
  echo "مسیر $SRC پیدا نشد."
  exit 1
fi

echo "==> rsync از $SRC به $REMOTE"

rsync -avz "$SRC/public/assets/admin/js/admin.js" "$REMOTE/public/assets/admin/js/"
rsync -avz "$SRC/public/vendor/ffmpeg/" "$REMOTE/public/vendor/ffmpeg/"
rsync -avz "$SRC/public/mix-manifest.json" "$REMOTE/public/"

rsync -avz "$SRC/app/Services/IndexBannerVideoEncoder.php" "$REMOTE/app/Services/"
rsync -avz "$SRC/app/Http/Controllers/Admin/SettingController.php" "$REMOTE/app/Http/Controllers/Admin/"
rsync -avz "$SRC/app/Helpers/helpers.php" "$REMOTE/app/Helpers/"
rsync -avz "$SRC/routes/web.php" "$REMOTE/routes/"
rsync -avz "$SRC/public/.htaccess" "$REMOTE/public/"
rsync -avz "$SRC/resources/views/admin/setting/pages/index-page.blade.php" \
  "$REMOTE/resources/views/admin/setting/pages/"
rsync -avz "$SRC/resources/js/components/admin/IndexBannerVideoUpload.vue" \
  "$REMOTE/resources/js/components/admin/"
rsync -avz "$SRC/resources/js/src/compressUploadBannerVideo.js" \
  "$REMOTE/resources/js/src/"

echo ""
echo "==> تمام. روی سرور:"
echo "  cd /var/www/vilafarda && php artisan route:cache && php artisan view:clear"
