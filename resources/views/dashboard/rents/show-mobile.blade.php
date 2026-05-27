@extends('layouts.main.main_mobile', ['title' => __('title.rent_home', ['home' => $rent->home->name])])

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@php
    $showRentLocationMap = $rent->home->latitude && $rent->home->longitude
        && in_array($rent->status, [
            \App\Models\Order::WAITING_FOR_RENTER,
            \App\Models\Order::IN_RENT,
        ], true);
    $showRentHostCard = ! $rent->isPreContract() && $rent->owner;
@endphp

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/rent-show-mobile.css') }}">
    @if($showRentLocationMap)
        <link rel="stylesheet" href="{{ asset('vendor/leaflet/dist/leaflet.css') }}">
    @endif
@endsection

@section('content')
    @include('layouts.main.partials.navbar-mobile')

    @php
        use App\Models\Order;

        $doneStepCount = collect($tripSteps)->where('state', 'done')->count();
        $progressPercent = count($tripSteps) > 1
            ? max(0, min(100, (($doneStepCount - 1) / (count($tripSteps) - 1)) * 100))
            : 0;
        $displayPrice = $rent->discount ? $rent->payable_price : $rent->price;
        $homeCode = $rent->home->code ?? $rent->home->id;
    @endphp

    <div class="rent-show-page">
        @if(! $isUnsuccessful)
            <div class="rent-stepper" aria-label="مراحل رزرو">
                <div class="rent-stepper__track">
                    <div class="rent-stepper__progress-line" style="width: calc({{ $progressPercent }}% - 28px);"></div>

                    @foreach($tripSteps as $step)
                        <div class="rent-stepper__step rent-stepper__step--{{ $step['state'] }}{{ $step['is_current'] ? ' rent-stepper__step--current' : '' }}">
                            <span class="rent-stepper__dot" aria-hidden="true">
                                @if($step['state'] === 'done')
                                    <i class="bi bi-check-lg"></i>
                                @endif
                            </span>
                            <span class="rent-stepper__label">{{ $step['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="rent-status-card">
            <div class="rent-status-card__icon{{ $statusContent['icon_modifier'] ? ' rent-status-card__icon--' . $statusContent['icon_modifier'] : '' }}">
                <i class="bi {{ $statusContent['icon'] }}" aria-hidden="true"></i>
            </div>

            <h1 class="rent-status-card__title">{{ $statusContent['title'] }}</h1>
            <p class="rent-status-card__text">{{ $statusContent['text'] }}</p>

            @if(($canRenterCancel ?? false) && $rent->status === Order::PENDING)
                @include('dashboard.partials.order-deadline-countdown', [
                    'deadline' => $pendingDeadline,
                    'label' => 'مهلت تأیید میزبان',
                ])
            @elseif(($canRenterPay ?? false) && $rent->status === Order::AWAITING_PAYMENT)
                @include('dashboard.partials.order-deadline-countdown', [
                    'deadline' => $paymentDeadline,
                    'label' => 'مهلت پرداخت',
                ])
            @endif

            <hr class="rent-status-card__divider">

            <div class="rent-status-card__home">
                <img src="{{ $rent->home->cover_path }}"
                     alt="{{ $rent->home->name }}"
                     class="rent-status-card__thumb"
                     loading="lazy"
                     decoding="async">
                <div class="rent-status-card__home-info">
                    <h2 class="rent-status-card__home-name">{{ $rent->home->name }}</h2>
                    <span class="rent-status-card__code">کد: {{ persianNumber($homeCode) }}</span>
                    <a href="{{ route('main.homes.show', $rent->home) }}" class="rent-status-card__home-link">
                        مشاهده اقامتگاه
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            @if($canDownloadContract ?? false)
                <div class="rent-status-card__actions">
                    <a href="{{ route('dashboard.rents.contract', $rent) }}"
                       class="rent-status-card__btn rent-status-card__btn--pay w-100 text-decoration-none">
                        <i class="bi bi-download" aria-hidden="true"></i>
                        @lang('title.download_voucher')
                    </a>
                </div>
            @endif

            @if(($canRenterPay ?? false) || ($canRenterCancel ?? false))
                <div class="rent-status-card__actions">
                    @if($canRenterPay ?? false)
                        <form action="{{ route('dashboard.rents.pay', $rent) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="rent-status-card__btn rent-status-card__btn--pay w-100">
                                <i class="bi bi-credit-card" aria-hidden="true"></i>
                                پرداخت هزینه
                            </button>
                        </form>
                    @endif

                    @if($canRenterCancel ?? false)
                        <button type="button"
                                class="rent-status-card__btn rent-status-card__btn--cancel w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#cancelModal">
                            لغو رزرو
                        </button>
                    @endif
                </div>
            @endif
        </div>

        @if($canReview)
            <div class="rent-review-card">
                <div class="rent-review-card__accent" aria-hidden="true"></div>
                <div class="rent-review-card__body">
                    <div class="rent-review-card__content">
                        <h3 class="rent-review-card__title">
                            نظر شما مهم است!
                            <i class="bi bi-chat-dots" aria-hidden="true"></i>
                        </h3>
                        <p class="rent-review-card__text">
                            لطفاً به این رزرو امتیاز بدهید و راهنمای مسافرین بعدی باشید
                        </p>
                    </div>
                    <a href="{{ route('dashboard.rents.review.create', $rent) }}"
                       class="rent-review-card__btn">
                        ثبت نظر
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        @endif

        <div class="rent-details-card">
            <h3 class="rent-details-card__title">جزئیات رزرو</h3>
            <div class="rent-details-card__grid">
                <div class="rent-details-card__item">
                    <span class="rent-details-card__label">تاریخ ورود</span>
                    <span class="rent-details-card__value">{{ $rent->start_date }}</span>
                </div>
                <div class="rent-details-card__item">
                    <span class="rent-details-card__label">تاریخ خروج</span>
                    <span class="rent-details-card__value">{{ $rent->end_date }}</span>
                </div>
                <div class="rent-details-card__item">
                    <span class="rent-details-card__label">تعداد مهمان</span>
                    <span class="rent-details-card__value">{{ persianNumber($rent->count_guest) }} نفر</span>
                </div>
                <div class="rent-details-card__item">
                    <span class="rent-details-card__label">مبلغ</span>
                    <span class="rent-details-card__value">
                        @if($rent->discount)
                            <span style="text-decoration: line-through; color: #999; font-size: 11px;">{{ persianNumber($rent->price) }}</span>
                        @endif
                        {{ persianNumber($displayPrice) }} تومان
                    </span>
                </div>
                <div class="rent-details-card__item">
                    <span class="rent-details-card__label">وضعیت</span>
                    <span class="rent-details-card__value">{{ app(\App\Services\OrderShowPresenter::class)->displayStatusLabel($rent, 'renter') }}</span>
                </div>
                @if($rent->paid_at)
                    <div class="rent-details-card__item">
                        <span class="rent-details-card__label">پرداخت</span>
                        <span class="rent-details-card__value" style="color: #2e9e5a;">پرداخت شده</span>
                    </div>
                @endif
            </div>
        </div>

        @if($showRentLocationMap)
            @include('dashboard.partials.rent-location-map', [
                'rent' => $rent,
                'locationMapPrecise' => true,
            ])
        @endif

        @if($showRentHostCard)
            @include('dashboard.partials.rent-host-card', ['rent' => $rent])
        @endif

        @include('dashboard.partials.order-show-invoice', ['rent' => $rent, 'invoice' => $invoice])

        @include('dashboard.partials.order-show-rules', ['rent' => $rent, 'cancelPolicy' => $cancelPolicy])

        @include('dashboard.partials.order-contract-download', [
            'rent' => $rent,
            'canDownloadContract' => $canDownloadContract,
            'contractRoute' => route('dashboard.rents.contract', $rent),
        ])

        @if($rent->status === Order::AWAITING_PAYMENT)
            <rent-discount
                discount_route="{{ route('dashboard.rents.discount', $rent) }}"
                :has_discount="{{ $rent->discount ? 'true' : 'false' }}"
                :original_price="{{ $rent->price }}"
                :discount_amount="{{ $rent->discount }}"
            ></rent-discount>
        @endif

        <a href="{{ route('dashboard.rents.index') }}" class="rent-status-card__btn rent-status-card__btn--back d-block text-center text-decoration-none">
            <i class="bi bi-arrow-right" aria-hidden="true"></i>
            بازگشت به سفرهای من
        </a>
    </div>

    <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-body text-center py-4">
                    <h5 class="fw-bold mb-2">لغو رزرو</h5>
                    <p class="text-muted mb-4" style="font-size: 14px;">آیا مطمئن هستید که می‌خواهید این رزرو را لغو کنید؟</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                        <form action="{{ route('dashboard.rents.cancel', $rent) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger">لغو کن</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@if($showRentLocationMap)
    @section('scripts')
        <script src="{{ asset('vendor/leaflet/dist/leaflet.js') }}"></script>
        <script src="{{ asset('assets/js/rent-location-map.js') }}"></script>
    @endsection
@endif
