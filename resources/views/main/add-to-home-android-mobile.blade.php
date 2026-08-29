@extends('layouts.main.main_mobile', ['title' => 'نصب اپلیکیشن — اندروید'])

@section('content')
    @include('layouts.main.partials.navbar-mobile')

    @php
        $apkPath = public_path('app/'.config('pwa.apk_filename', 'vilafarda.apk'));
        $hasApk = is_file($apkPath);
    @endphp

    <div class="add-to-home-page">
        <div class="add-to-home-page__hero">
            <i class="bi bi-android2" aria-hidden="true"></i>
            <h1>نصب اپلیکیشن</h1>
            <p>نسخه اندروید {{ siteName() }} — همان سایت موبایل، به‌صورت برنامه قابل نصب</p>
        </div>

        <div class="container px-3 py-4">
            @if($hasApk)
                <a href="{{ route('pwa.apk') }}" class="btn btn-primary w-100 mb-4" style="background: #D39D1A; border-color: #D39D1A; border-radius: 12px; padding: 14px; font-size: 16px;">
                    <i class="bi bi-download ms-1" aria-hidden="true"></i>
                    دانلود مستقیم فایل نصب (APK)
                </a>
                <p class="text-muted small">اگر گوشی نصب از منابع ناشناس را پرسید، اجازه بدهید. حجم فایل کم است چون برنامه همان سایت را باز می‌کند.</p>
            @else
                <p class="text-muted">فایل نصب به‌زودی همین‌جا قرار می‌گیرد. تا آن موقع می‌توانید میانبر Chrome را بسازید:</p>
            @endif

            <div class="add-to-home-step">
                <span class="add-to-home-step__number">۱</span>
                <div class="add-to-home-step__body">
                    <h3>باز کردن سایت در Chrome</h3>
                    <p>آدرس سایت را در مرورگر <strong>Google Chrome</strong> باز کنید.</p>
                </div>
            </div>

            <div class="add-to-home-step">
                <span class="add-to-home-step__number">۲</span>
                <div class="add-to-home-step__body">
                    <h3>منوی مرورگر</h3>
                    <p>روی آیکون سه‌نقطه <i class="bi bi-three-dots-vertical"></i> در گوشه بالا بزنید.</p>
                </div>
            </div>

            <div class="add-to-home-step">
                <span class="add-to-home-step__number">۳</span>
                <div class="add-to-home-step__body">
                    <h3>افزودن به صفحه اصلی</h3>
                    <p>گزینه <strong>Add to Home screen</strong> یا <strong>نصب برنامه</strong> را انتخاب کنید.</p>
                </div>
            </div>

            <div class="add-to-home-step">
                <span class="add-to-home-step__number">۴</span>
                <div class="add-to-home-step__body">
                    <h3>تأیید نصب</h3>
                    <p>در پنجره بازشده روی <strong>Add</strong> یا <strong>نصب</strong> بزنید.</p>
                </div>
            </div>

            <a href="{{ route('main.index') }}" class="btn btn-outline-secondary w-100 mt-3" style="border-radius: 12px;">
                بازگشت به صفحه اصلی
            </a>
        </div>
    </div>
@endsection
