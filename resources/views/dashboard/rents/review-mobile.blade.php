@extends('layouts.main.main_mobile', ['title' => 'ثبت نظر'])

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/review-mobile.css') }}">
@endsection

@section('content')
    @php
        $host = $rent->owner;
        $hostAvatar = $host?->avatar_path ?? \App\Models\User::getDefaultAvatar();
    @endphp

    <div class="review-page">
        <div class="review-topbar">
            <a href="{{ route('dashboard.rents.index', ['tab' => \App\Models\Order::TRIP_TAB_AWAITING_REVIEW]) }}"
               class="review-back">
                <i class="bi bi-chevron-right" aria-hidden="true"></i>
                سفرها
            </a>
            @auth
                <a href="{{ route('dashboard.profile.edit') }}" class="d-flex align-items-center">
                    <img src="{{ auth()->user()->avatar_path }}"
                         alt=""
                         width="36"
                         height="36"
                         class="rounded-circle"
                         style="object-fit: cover;">
                </a>
            @endauth
        </div>

        <div class="review-hero">
            <img src="{{ $rent->home->cover_path }}"
                 alt="{{ $rent->home->name }}"
                 class="review-hero__img"
                 loading="eager"
                 decoding="async">
            <div class="review-hero__overlay" aria-hidden="true"></div>
            <div class="review-hero__content">
                <h1 class="review-hero__title">{{ $rent->home->name }}</h1>
                <p class="review-hero__meta">میزبان: {{ $hostName }}</p>
                @if($travelDate)
                    <p class="review-hero__meta">تاریخ سفر: {{ $travelDate }}</p>
                @endif
            </div>
            <img src="{{ $hostAvatar }}"
                 alt="{{ $hostName }}"
                 class="review-hero__avatar"
                 width="48"
                 height="48"
                 loading="lazy"
                 decoding="async">
        </div>

        <div class="review-card">
            <p class="review-card__intro">
                با ثبت نظر خود به مهمانان بعدی در انتخاب اقامتگاه کمک کنید. امتیاز شما پس از بررسی نمایش داده می‌شود.
            </p>

            <h2 class="review-card__heading">ثبت امتیاز:</h2>

            <form id="reviewForm"
                  action="{{ route('dashboard.rents.review.store', $rent) }}"
                  method="POST">
                @csrf

                @foreach($criteria as $key => $label)
                    @php
                        $oldScore = (int) old("scores.{$key}", 0);
                    @endphp
                    <div class="review-criterion" data-criterion="{{ $key }}">
                        <label class="review-criterion__label" for="scores-{{ $key }}">{{ $label }}</label>
                        <div class="review-stars" role="group" aria-label="{{ $label }}">
                            @for($star = 1; $star <= 5; $star++)
                                <button type="button"
                                        class="review-stars__btn{{ $oldScore >= $star ? ' is-active' : '' }}"
                                        data-value="{{ $star }}"
                                        aria-label="{{ persianNumber($star) }} ستاره">
                                    <i class="bi bi-star-fill" aria-hidden="true"></i>
                                </button>
                            @endfor
                        </div>
                        <p class="review-stars__caption" data-caption aria-live="polite">
                            @if($oldScore > 0)
                                {{ persianNumber($oldScore) }} ستاره
                            @endif
                        </p>
                        <input type="hidden"
                               name="scores[{{ $key }}]"
                               id="scores-{{ $key }}"
                               value="{{ $oldScore ?: '' }}">
                        @error("scores.{$key}")
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach

                <div class="review-comment">
                    <label class="review-criterion__label" for="review-comment">متن نظر (اختیاری)</label>
                    <textarea id="review-comment"
                              name="comment"
                              rows="4"
                              placeholder="تجربه اقامت خود را بنویسید...">{{ old('comment') }}</textarea>
                    @error('comment')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </form>
        </div>

        <div class="review-footer">
            <button type="submit"
                    form="reviewForm"
                    id="reviewSubmitBtn"
                    class="review-footer__submit"
                    disabled>
                ثبت نظر و امتیاز
            </button>
            <p class="review-footer__hint" id="reviewFooterHint">لطفاً به همه آیتم‌ها امتیاز دهید.</p>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/review-mobile.js') }}"></script>
@endsection
