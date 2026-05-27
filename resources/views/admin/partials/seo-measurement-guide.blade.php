<div class="alert alert-secondary border small mb-3">
    <h6 class="alert-heading mb-2">
        <span class="fas fa-chart-line ms-1"></span>
        راهنمای مدیر — ابزارهای اندازه‌گیری (خارج از پنل)
    </h6>
    <p class="mb-2 small">
        گزارش‌ها را در <strong>سایت خود گوگل</strong> می‌بینید؛ نیازی به ویجت داخل پنل ادمین نیست.
        اسکریپت GA4 فقط روی <strong>صفحات عمومی</strong> بارگذاری می‌شود (نه پنل ادمین و نه داشبورد میزبان/مهمان).
    </p>
    <table class="table table-sm table-bordered mb-2 small bg-white">
        <thead>
            <tr>
                <th>ابزار</th>
                <th>کار شما</th>
                <th>کجا گزارش بگیرید</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Google Analytics 4</strong></td>
                <td>از <a href="https://analytics.google.com" target="_blank" rel="noopener">analytics.google.com</a> یک Property بسازید؛ شناسه <code>G-XXXXXXXX</code> را در فیلد پایین وارد کنید.</td>
                <td>همان پنل GA4 — رویداد «خرید» پس از پرداخت موفق در صفحه بازگشت از درگاه ثبت می‌شود.</td>
            </tr>
            <tr>
                <td><strong>Google Search Console</strong></td>
                <td>دامنه سایت را اضافه کنید؛ کد تأیید را در فیلد «کد تأیید» همین صفحه بگذارید و ذخیره کنید.</td>
                <td><a href="https://search.google.com/search-console" target="_blank" rel="noopener">search.google.com/search-console</a> — ایندکس، خطا، کوئری</td>
            </tr>
            <tr>
                <td><strong>PageSpeed Insights</strong></td>
                <td>نیازی به تنظیم در سایت نیست؛ بعد از تغییر تصاویر یا بنر، آدرس صفحه اصلی را تست کنید.</td>
                <td><a href="https://pagespeed.web.dev" target="_blank" rel="noopener">pagespeed.web.dev</a> — LCP، INP، CLS</td>
            </tr>
        </tbody>
    </table>
    <ul class="mb-0 small ps-3">
        <li class="mb-1">برای <strong>تبدیل رزرو</strong> در GA4: رویداد <code>purchase</code> را به‌عنوان Conversion علامت بزنید (Admin → Events).</li>
        <li class="mb-1">اگر GA4 نمی‌خواهید، فیلد شناسه را خالی بگذارید؛ می‌توانید از Plausible یا ابزار دیگر با کمک تیم فنی استفاده کنید.</li>
        <li class="mb-0">بازدیدهای خودتان در پنل ادمین در آمار GA4 شمرده <strong>نمی‌شود</strong> — عمدی است.</li>
    </ul>
</div>
