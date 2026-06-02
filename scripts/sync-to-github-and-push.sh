#!/usr/bin/env bash
# ۱) rentnaab → rentnaab-github  ۲) build  ۳) commit + push github
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
DST="/opt/lampp/htdocs/rentnaab-github"

"$SCRIPT_DIR/sync-rentnaab-to-github.sh"

cd "$DST"
echo "==> npm run production در rentnaab-github"
npm run production

echo "==> git add"
git add -A
git add -f public/assets/admin/js/admin.js 2>/dev/null || true

git status -sb

if git diff --cached --quiet; then
  echo "چیزی برای commit نیست."
  exit 0
fi

git commit -m "$(cat <<'EOF'
fix: تقویم اقامتگاه — بسته بودن مهمان، ادمین/میزبان و عملکرد

- رفع JSON آبجکت disable_dates که روزهای بسته به مهمان نمی‌رسید
- ادمین: انتخاب روز رزرو/بسته؛ نمایش برچسب پر و بسته
- میزبان: روز رزرو فعال غیرقابل ویرایش (فرانت و بک‌اند)
- مهمان: فقط کم‌رنگ شدن روز غیرفعال، بدون برچسب پر/بسته
- بهبود سرعت تقویم ادمین (کش روزها و کلید ماه پایدار)

EOF
)"

git push github main

echo ""
echo "Push شد. deploy سرور:"
echo "  cd $DST && ./scripts/deploy-banner-video-to-server.sh"
