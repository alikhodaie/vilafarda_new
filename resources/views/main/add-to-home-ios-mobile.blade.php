@extends('layouts.main.main_mobile', ['title' => 'افزودن به صفحه اصلی — آیفون'])

@section('content')
    @include('layouts.main.partials.navbar-mobile')

    <div class="add-to-home-page">
        <div class="add-to-home-page__hero">
            <i class="bi bi-apple" aria-hidden="true"></i>
            <h1>افزودن به صفحه اصلی</h1>
            <p>راهنمای نصب {{ config('app.name') }} روی آیفون (Safari)</p>
        </div>

        <div class="container px-3 py-4">
            <div class="add-to-home-step">
                <span class="add-to-home-step__number">۱</span>
                <div class="add-to-home-step__body">
                    <h3>باز کردن سایت در Safari</h3>
                    <p>آدرس سایت را در مرورگر <strong>Safari</strong> باز کنید. این روش در Chrome آیفون کار نمی‌کند.</p>
                </div>
            </div>

            <div class="add-to-home-step">
                <span class="add-to-home-step__number">۲</span>
                <div class="add-to-home-step__body">
                    <h3>دکمه اشتراک‌گذاری</h3>
                    <p>در پایین صفحه روی آیکون <i class="bi bi-box-arrow-up"></i> (Share) بزنید.</p>
                </div>
            </div>

            <div class="add-to-home-step">
                <span class="add-to-home-step__number">۳</span>
                <div class="add-to-home-step__body">
                    <h3>افزودن به صفحه اصلی</h3>
                    <p>گزینه <strong>Add to Home Screen</strong> یا <strong>افزودن به صفحه اصلی</strong> را انتخاب کنید.</p>
                </div>
            </div>

            <div class="add-to-home-step">
                <span class="add-to-home-step__number">۴</span>
                <div class="add-to-home-step__body">
                    <h3>تأیید نهایی</h3>
                    <p>در بالا سمت راست روی <strong>Add</strong> یا <strong>افزودن</strong> بزنید. آیکون برنامه روی صفحه اصلی ظاهر می‌شود.</p>
                </div>
            </div>

            <a href="{{ route('main.index') }}" class="btn btn-primary w-100 mt-3" style="background: #D39D1A; border-color: #D39D1A; border-radius: 12px;">
                بازگشت به صفحه اصلی
            </a>
        </div>
    </div>
@endsection
