@extends('layouts.main.main_mobile', ['title' => 'افزودن به صفحه اصلی — اندروید'])

@section('content')
    @include('layouts.main.partials.navbar-mobile')

    <div class="add-to-home-page">
        <div class="add-to-home-page__hero">
            <i class="bi bi-android2" aria-hidden="true"></i>
            <h1>افزودن به صفحه اصلی</h1>
            <p>راهنمای نصب {{ config('app.name') }} روی اندروید (Chrome)</p>
        </div>

        <div class="container px-3 py-4">
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
                    <p>گزینه <strong>Add to Home screen</strong> یا <strong>افزودن به صفحه اصلی</strong> را انتخاب کنید.</p>
                </div>
            </div>

            <div class="add-to-home-step">
                <span class="add-to-home-step__number">۴</span>
                <div class="add-to-home-step__body">
                    <h3>تأیید نصب</h3>
                    <p>در پنجره بازشده روی <strong>Add</strong> یا <strong>نصب</strong> بزنید. میانبر برنامه روی صفحه اصلی اضافه می‌شود.</p>
                </div>
            </div>

            <p class="text-muted small text-center mt-2 mb-0">
                در برخی گوشی‌ها ممکن است گزینه «نصب برنامه» یا «Install app» نمایش داده شود.
            </p>

            <a href="{{ route('main.index') }}" class="btn btn-primary w-100 mt-3" style="background: #D39D1A; border-color: #D39D1A; border-radius: 12px;">
                بازگشت به صفحه اصلی
            </a>
        </div>
    </div>
@endsection
