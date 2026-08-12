#!/usr/bin/env bash
# فقط فایل‌های همگام‌سازی تقویم جاباما را به سرور می‌فرستد — بدون دست زدن به بقیه پروژه.
set -euo pipefail

ROOT="/opt/lampp/htdocs/rentnaab-github"
SERVER="${DEPLOY_HOST:-root@158.255.74.248}"
REMOTE_DIR="${DEPLOY_PATH:-/var/www/vilafarda}"

FILES=(
  "app/Services/ExternalCalendar/JabamaCalendarFetcher.php"
  "app/Services/ExternalCalendar/ExternalCalendarSyncService.php"
  "app/Support/ExternalCalendarPlatform.php"
  "app/Support/ExternalCalendarSyncCooldown.php"
  "app/Http/Controllers/Admin/Home/HomeCalendarSyncController.php"
  "resources/views/admin/homes/calendar-sync/index.blade.php"
)

cd "$ROOT"

echo "==> بررسی وجود فایل‌ها"
for f in "${FILES[@]}"; do
  if [[ ! -s "$f" ]]; then
    echo "MISSING/EMPTY: $f" >&2
    exit 1
  fi
  echo "  OK $f ($(wc -c < "$f") bytes)"
done

echo "==> ارسال به $SERVER:$REMOTE_DIR"
for f in "${FILES[@]}"; do
  remote_dir="$REMOTE_DIR/$(dirname "$f")"
  ssh -o BatchMode=yes -o ConnectTimeout=15 "$SERVER" "mkdir -p '$remote_dir'"
  scp -o BatchMode=yes -o ConnectTimeout=15 "$f" "$SERVER:$REMOTE_DIR/$f"
done

echo "==> پاک‌سازی کش روی سرور"
ssh -o BatchMode=yes -o ConnectTimeout=15 "$SERVER" "cd '$REMOTE_DIR' && php artisan view:clear && php artisan route:clear && php artisan config:clear"

echo ""
echo "تمام. فقط تقویم جاباما آپدیت شد."
echo "تست: /admin/homes/calendar-sync با لینک jabama"
