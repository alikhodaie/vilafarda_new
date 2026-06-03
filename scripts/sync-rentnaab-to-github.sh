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
  app/Classes/Zarinpal.php
  app/Classes/Payment/SizpayClient.php
  app/Classes/Payment/Gateway/Zarinpal.php
  app/Classes/Payment/Gateway/IDPay.php
  app/Classes/Payment/Gateway/Sizpay.php
  app/Exceptions/Handler.php
  app/Helpers/helpers.php
  app/Http/Controllers/Admin/SettingController.php
  app/Http/Controllers/Dashboard/RentController.php
  app/Http/Controllers/Main/MainController.php
  app/Http/Requests/Dashboard/Rent/PayRentRequest.php
  app/Models/Transaction.php
  app/Providers/SettingServiceProvider.php
  app/Services/PaymentGatewayService.php
  database/migrations/2026_06_02_120000_add_payment_gateway_settings.php
  database/migrations/2026_06_02_130000_add_sizpay_payment_gateway.php
  app/Http/Controllers/Api/AuthController.php
  app/Http/Requests/Dashboard/Home/DestroyCustomDateRequest.php
  app/Http/Requests/Dashboard/Home/StoreCustomDateRequest.php
  app/Jobs/CancelUndeterminedOrders.php
  app/Models/Home.php
  app/Models/Order.php
  app/Services/IndexBannerVideoEncoder.php
  resources/lang/fa/text.php
  resources/lang/fa/title.php
  resources/views/admin/setting/pages/payment.blade.php
  resources/views/admin/homes/date.blade.php
  resources/views/admin/setting/pages/index-page.blade.php
  resources/views/dashboard/homes/custom/date-mobile.blade.php
  resources/views/dashboard/homes/custom/date.blade.php
  resources/views/dashboard/rents/pay.blade.php
  resources/views/dashboard/rents/pay-mobile.blade.php
  resources/views/dashboard/rents/partials/actions.blade.php
  resources/views/dashboard/rents/show-mobile.blade.php
  resources/views/dashboard/rents/index-mobile.blade.php
  resources/views/main/sizpay-checkout.blade.php
  public/assets/css/rent-pay-mobile.css
  resources/views/main/auth/login-mobile.blade.php
  resources/js/components/admin/IndexBannerVideoUpload.vue
  resources/js/components/main/Home/CustomDate.vue
  resources/js/components/main/PersianCalendar.vue
  resources/js/components/main/ReserveHome.vue
  resources/js/src/compressUploadBannerVideo.js
  resources/js/src/mixin.js
  resources/views/main/homes/show.blade.php
  resources/views/main/homes/show_mobile.blade.php
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
