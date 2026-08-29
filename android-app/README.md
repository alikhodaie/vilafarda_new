# اپ اندروید ویلا فردا (TWA)

این پوشه یک Trusted Web Activity است: برنامه فقط `https://www.vilafarda.ir` را تمام‌صفحه باز می‌کند. ظاهر و به‌روزرسانی همان سایت موبایل است.

## فایل امضا (خیلی مهم)

کلید نصب در `android-app/keystore/` است و داخل git نمی‌رود.

- `keystore/vilafarda.keystore`
- `keystore/password.txt`
- `keystore.properties`

اگر این‌ها گم شوند نمی‌توانید آپدیت همان اپ را با همان نام بسته منتشر کنید. یک کپی در جای امن نگه دارید.

## ساخت APK با GitHub Actions

دانلود SDK گوگل از این سیستم لوکال ممکن است مسدود باشد. ساخت روی GitHub انجام می‌شود:

1. در GitHub: Settings → Secrets and variables → Actions این دو secret را بگذارید:
   - `ANDROID_KEYSTORE_BASE64` خروجی دستور زیر:
     `base64 -w0 android-app/keystore/vilafarda.keystore`
   - `ANDROID_KEYSTORE_PASSWORD` همان محتوای `keystore/password.txt`
2. workflow به نام `Build Android TWA` را Run کنید.
3. از Artifacts فایل APK را دانلود کنید و روی سرور در `public/app/vilafarda.apk` بگذارید.

## ساخت روی سیستم خودتان (اگر Android Studio نصب باشد)

```bash
export ANDROID_HOME=$HOME/Android/Sdk
cd android-app
./gradlew assembleRelease
```

خروجی: `app/build/outputs/apk/release/app-release.apk`
