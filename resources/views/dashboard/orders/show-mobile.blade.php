@extends('layouts.main.main_mobile', ['title' => 'جزئیات رزرو'])

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/rent-show-mobile.css') }}">
@endsection

@section('content')
    @include('layouts.main.partials.navbar-mobile')

    @php
        use App\Models\Order;

        if ($isUnsuccessful) {
            $progressPercent = $unsuccessfulProgressPercent;
        } else {
            $doneStepCount = collect($tripSteps)->where('state', 'done')->count();
            $progressPercent = count($tripSteps) > 1
                ? max(0, min(100, (($doneStepCount - 1) / (count($tripSteps) - 1)) * 100))
                : 0;
        }
        $homeCode = $order->home->code ?? $order->id;
        $renterName = $order->renter?->full_name ?? 'مهمان';
        $showOrderGuestCard = ! $order->isPreContract() && $order->renter;
    @endphp

    <div class="rent-show-page">
        <div class="rent-stepper{{ $isUnsuccessful ? ' rent-stepper--unsuccessful' : '' }}" aria-label="مراحل رزرو">
            <div class="rent-stepper__track">
                <div class="rent-stepper__progress-line{{ $isUnsuccessful ? ' rent-stepper__progress-line--unsuccessful' : '' }}"
                     style="width: calc({{ $progressPercent }}% - 28px);"></div>

                @foreach($tripSteps as $index => $step)
                    <div class="rent-stepper__step rent-stepper__step--{{ $step['state'] }}{{ $step['is_current'] ? ' rent-stepper__step--current' : '' }}">
                        <span class="rent-stepper__dot" aria-hidden="true">
                            @if($step['state'] === 'done')
                                <i class="bi bi-check-lg"></i>
                            @elseif($step['state'] === 'failed')
                                <i class="bi bi-x-lg"></i>
                            @elseif($isUnsuccessful && $step['state'] === 'pending')
                                {{ persianNumber($index + 1) }}
                            @endif
                        </span>
                        <span class="rent-stepper__label">{{ $step['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rent-status-card{{ $isUnsuccessful ? ' rent-status-card--unsuccessful' : '' }}">
            <div class="rent-status-card__icon{{ $statusContent['icon_modifier'] ? ' rent-status-card__icon--' . $statusContent['icon_modifier'] : '' }}">
                <i class="bi {{ $statusContent['icon'] }}" aria-hidden="true"></i>
            </div>

            <h1 class="rent-status-card__title">{{ $statusContent['title'] }}</h1>
            <p class="rent-status-card__text">{{ $statusContent['text'] }}</p>

            @if(($canHostRespondToPending ?? false) && $order->status === Order::PENDING)
                @include('dashboard.partials.order-deadline-countdown', [
                    'deadline' => $pendingDeadline,
                    'label' => 'مهلت تأیید درخواست',
                ])
            @elseif($order->status === Order::AWAITING_PAYMENT && ! $order->isPaymentDeadlinePassed())
                @include('dashboard.partials.order-deadline-countdown', [
                    'deadline' => $paymentDeadline,
                    'label' => 'مهلت پرداخت مهمان',
                ])
            @endif

            <hr class="rent-status-card__divider">

            <div class="rent-status-card__home">
                <img src="{{ $order->home->cover_path }}"
                     alt="{{ $order->home->name }}"
                     class="rent-status-card__thumb"
                     loading="lazy"
                     decoding="async">
                <div class="rent-status-card__home-info">
                    <h2 class="rent-status-card__home-name">{{ $order->home->name }}</h2>
                    <span class="rent-status-card__code">کد: {{ persianNumber($homeCode) }}</span>
                    <a href="{{ route('main.homes.show', $order->home) }}" class="rent-status-card__home-link">
                        مشاهده اقامتگاه
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            @if($canDownloadContract ?? false)
                <div class="rent-status-card__actions">
                    <a href="{{ route('dashboard.orders.contract', $order) }}"
                       class="rent-status-card__btn rent-status-card__btn--pay w-100 text-decoration-none">
                        <i class="bi bi-download" aria-hidden="true"></i>
                        @lang('title.download_voucher')
                    </a>
                </div>
            @endif

            @if($canHostRespondToPending ?? false)
                <div class="rent-status-card__actions">
                    <form action="{{ route('dashboard.orders.accept', $order) }}" method="POST" class="m-0">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="rent-status-card__btn rent-status-card__btn--pay w-100">
                            <i class="bi bi-check-lg" aria-hidden="true"></i>
                            تایید درخواست
                        </button>
                    </form>
                    <button type="button"
                            class="rent-status-card__btn rent-status-card__btn--cancel w-100"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectModal">
                        رد درخواست
                    </button>
                </div>
            @endif
        </div>

        @if($isUnsuccessful)
            <div class="rent-cancel-reason" role="region" aria-label="{{ $rejectionReason['label'] }}">
                <div class="rent-cancel-reason__header">
                    <i class="bi bi-exclamation-circle-fill rent-cancel-reason__icon" aria-hidden="true"></i>
                    <h3 class="rent-cancel-reason__title">{{ $rejectionReason['label'] }}</h3>
                </div>
                <p class="rent-cancel-reason__text{{ $rejectionReason['is_placeholder'] ? ' rent-cancel-reason__text--placeholder' : '' }}">
                    @if($rejectionReason['is_placeholder'])
                        علت رد ثبت نشده است.
                    @else
                        {{ $rejectionReason['text'] }}
                    @endif
                </p>
            </div>
        @endif

        @if(! $isUnsuccessful)
        <div class="rent-details-card">
            <h3 class="rent-details-card__title">اطلاعات مهمان</h3>
            <div class="rent-details-card__grid">
                <div class="rent-details-card__item">
                    <span class="rent-details-card__label">مهمان</span>
                    <span class="rent-details-card__value">{{ $renterName }}</span>
                </div>
                <div class="rent-details-card__item">
                    <span class="rent-details-card__label">کد رزرو</span>
                    <span class="rent-details-card__value">{{ persianNumber($order->id) }}</span>
                </div>
                <div class="rent-details-card__item">
                    <span class="rent-details-card__label">تاریخ ورود</span>
                    <span class="rent-details-card__value">{{ $order->start_date }}</span>
                </div>
                <div class="rent-details-card__item">
                    <span class="rent-details-card__label">تاریخ خروج</span>
                    <span class="rent-details-card__value">{{ $order->end_date }}</span>
                </div>
                <div class="rent-details-card__item">
                    <span class="rent-details-card__label">تعداد مهمان</span>
                    <span class="rent-details-card__value">{{ persianNumber($order->count_guest) }} نفر</span>
                </div>
                <div class="rent-details-card__item">
                    <span class="rent-details-card__label">وضعیت</span>
                    <span class="rent-details-card__value">{{ app(\App\Services\OrderShowPresenter::class)->displayStatusLabel($order, 'host') }}</span>
                </div>
                @if($order->paid_at)
                    <div class="rent-details-card__item">
                        <span class="rent-details-card__label">پرداخت</span>
                        <span class="rent-details-card__value" style="color: #2e9e5a;">پرداخت شده</span>
                    </div>
                @endif
            </div>

            @if($order->description)
                <p class="rent-rules-card__body mt-3 mb-0">
                    <strong>توضیحات مهمان:</strong><br>{{ $order->description }}
                </p>
            @endif
        </div>

        @if($showOrderGuestCard)
            @include('dashboard.partials.order-guest-card', ['order' => $order])
        @endif

        @include('dashboard.partials.order-show-invoice', [
            'order' => $order,
            'invoice' => $invoice,
            'hostSettlement' => $hostSettlement,
        ])

        @include('dashboard.partials.order-show-rules', ['order' => $order, 'cancelPolicy' => $cancelPolicy])
        @endif

        @include('dashboard.partials.order-contract-download', [
            'order' => $order,
            'canDownloadContract' => $canDownloadContract,
            'contractRoute' => route('dashboard.orders.contract', $order),
        ])

        <a href="{{ route('dashboard.orders.index', ['tab' => $isCurrent ? 'current' : ($checkoutPassed ? 'finished' : 'unsuccessful')]) }}"
           class="rent-status-card__btn rent-status-card__btn--back d-block text-center text-decoration-none">
            <i class="bi bi-arrow-right" aria-hidden="true"></i>
            بازگشت به درخواست‌ها
        </a>
    </div>

    @include('dashboard.partials.order-reject-modal', ['order' => $order])
@endsection
