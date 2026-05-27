@extends('layouts.main.main_mobile', ['title' => __('title.guest_transactions')])

@section('styles')
    <style>
        .guest-tx-card {
            background: #fff;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .guest-tx-card__header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 8px;
        }
        .guest-tx-card__title {
            font-size: 14px;
            font-weight: 700;
            color: #333;
        }
        .guest-tx-card__amount {
            font-size: 18px;
            font-weight: 700;
            color: #198754;
            margin-bottom: 10px;
        }
        .guest-tx-card__meta li {
            font-size: 12px;
            color: #555;
            margin-bottom: 4px;
        }
        .guest-tx-card__link {
            display: inline-block;
            margin-top: 8px;
            font-size: 12px;
            color: #198754;
        }
        .guest-tx-badge {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 8px;
        }
        .guest-tx-badge--success { background: #d4edda; color: #155724; }
        .guest-tx-badge--warning { background: #fff3cd; color: #856404; }
        .guest-tx-badge--danger { background: #f8d7da; color: #721c24; }
    </style>
@endsection

@section('content')
    @include('layouts.main.partials.navbar-mobile')

    <div class="container px-3 py-3">
        <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <h1 class="fw-bold mb-2" style="font-size: 18px; color: #333;">@lang('title.guest_transactions')</h1>
            <p class="mb-0" style="font-size: 13px; color: #666;">پرداخت‌های شما برای رزرو اقامتگاه‌ها</p>
        </div>
    </div>

    <div class="container px-3 pb-4">
        @if($transactions->isNotEmpty())
            @foreach($transactions as $transaction)
                @php
                    $order = $transaction->orders->first();
                    $home = $order?->home;
                    $checkinJ = $order?->checkin ? persianDate($order->checkin->copy()) : null;
                    $checkoutCarbon = $order?->checkout ? $order->checkout->copy()->addDay() : null;
                    $checkoutJ = $checkoutCarbon ? persianDate($checkoutCarbon) : null;
                    $effectiveStatus = $transaction->effectiveStatus();
                    $badgeClass = match ($effectiveStatus) {
                        \App\Models\Transaction::SUCCESS => 'guest-tx-badge--success',
                        \App\Models\Transaction::FAILED => 'guest-tx-badge--danger',
                        default => 'guest-tx-badge--warning',
                    };
                @endphp

                <div class="guest-tx-card">
                    <div class="guest-tx-card__header">
                        <div>
                            <div class="guest-tx-card__title">{{ $home?->name ?? $transaction->description }}</div>
                            @if($home?->code)
                                <small class="text-muted">کد: {{ $home->code }}</small>
                            @endif
                        </div>
                        <span class="guest-tx-badge {{ $badgeClass }}">{{ $transaction->status('text', $effectiveStatus) }}</span>
                    </div>

                    <div class="guest-tx-card__amount">
                        {{ number_format($transaction->price) }} @lang('title.toman')
                    </div>

                    <ul class="guest-tx-card__meta list-unstyled">
                        <li>درگاه: {{ $transaction->gateway() }}</li>
                        @if($transaction->reference)
                            <li>پیگیری: #{{ $transaction->reference }}</li>
                        @endif
                        @if($checkinJ && $checkoutJ)
                            <li>اقامت: {{ $checkinJ->format('j F') }} – {{ $checkoutJ->format('j F Y') }}</li>
                        @endif
                        @if($order)
                            <li>وضعیت رزرو: {{ app(\App\Services\OrderShowPresenter::class)->displayStatusLabel($order, 'renter') }}</li>
                        @endif
                        <li>{{ $transaction->persianCreatedAt('Y/m/d H:i') }}</li>
                    </ul>

                    @if($order)
                        <a href="{{ route('dashboard.rents.show', $order->id) }}" class="guest-tx-card__link">
                            مشاهده سفر
                        </a>
                    @endif
                </div>
            @endforeach

            <div class="text-center pt-2">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-credit-card fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">تراکنشی ثبت نشده است</h5>
            </div>
        @endif
    </div>
@endsection
