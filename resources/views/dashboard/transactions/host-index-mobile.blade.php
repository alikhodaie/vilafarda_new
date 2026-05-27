@extends('layouts.main.main_mobile', ['title' => __('title.host_transactions')])

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/trips-mobile.css') }}">
    <style>
        .host-payout-card {
            background: #fff;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .host-payout-card__header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 8px;
        }
        .host-payout-card__title {
            font-size: 14px;
            font-weight: 700;
            color: #333;
        }
        .host-payout-card__amount {
            font-size: 18px;
            font-weight: 700;
            color: #2c7be5;
            margin-bottom: 10px;
        }
        .host-payout-card__meta li {
            font-size: 12px;
            color: #555;
            margin-bottom: 4px;
        }
        .host-payout-card__link {
            display: inline-block;
            margin-top: 8px;
            font-size: 12px;
            color: #2c7be5;
        }
        .host-payout-badge {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 8px;
        }
        .host-payout-badge--warning { background: #fff3cd; color: #856404; }
        .host-payout-badge--success { background: #d4edda; color: #155724; }
        .host-payout-badge--danger { background: #f8d7da; color: #721c24; }
    </style>
@endsection

@section('content')
    @include('layouts.main.partials.navbar-mobile')

    @php
        use App\Models\HostPayout;

        $tabs = [
            HostPayout::TAB_PENDING => 'در انتظار پرداخت',
            HostPayout::TAB_PAID => 'پرداخت شده',
            HostPayout::TAB_CANCELLED => 'لغو شده',
        ];
    @endphp

    <div class="trips-page">
        <div class="trips-header">
            <div class="trips-breadcrumb">
                <a href="{{ route('dashboard.index') }}">داشبورد</a>
                <span class="mx-1">›</span>
                <span>@lang('title.host_transactions')</span>
            </div>

            <nav class="trips-tabs">
                @foreach($tabs as $tabKey => $tabLabel)
                    <a href="{{ route('dashboard.host-transactions.index', ['tab' => $tabKey]) }}"
                       class="trips-tab {{ $tab === $tabKey ? 'active' : '' }}">
                        {{ $tabLabel }} ({{ persianNumber($counts[$tabKey] ?? 0) }})
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="trips-content px-3 pb-4">
            <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <p class="mb-0" style="font-size: 12px; color: #666;">
                    پس از پرداخت مهمان، مبلغ تسویه هر رزرو جداگانه در این بخش نمایش داده می‌شود.
                </p>
            </div>

            @if($payouts->isNotEmpty())
                @foreach($payouts as $payout)
                    @php
                        $order = $payout->order;
                        $home = $order?->home;
                        $renter = $order?->renter;
                        $checkinJ = $order?->checkin ? persianDate($order->checkin->copy()) : null;
                        $checkoutCarbon = $order?->checkout ? $order->checkout->copy()->addDay() : null;
                        $checkoutJ = $checkoutCarbon ? persianDate($checkoutCarbon) : null;
                        $badgeClass = match ($payout->status) {
                            HostPayout::PAID => 'host-payout-badge--success',
                            HostPayout::CANCELLED => 'host-payout-badge--danger',
                            default => 'host-payout-badge--warning',
                        };
                    @endphp

                    <div class="host-payout-card">
                        <div class="host-payout-card__header">
                            <div>
                                <div class="host-payout-card__title">{{ $home?->name ?? '—' }}</div>
                                <small class="text-muted">کد: {{ $home?->code ?? '—' }}</small>
                            </div>
                            <span class="host-payout-badge {{ $badgeClass }}">{{ $payout->status() }}</span>
                        </div>

                        <div class="host-payout-card__amount">
                            {{ number_format($payout->amount) }} @lang('title.toman')
                        </div>

                        <ul class="host-payout-card__meta list-unstyled">
                            <li>مهمان: {{ $renter?->full_name ?? '—' }}</li>
                            @if($checkinJ && $checkoutJ)
                                <li>اقامت: {{ $checkinJ->format('j F') }} – {{ $checkoutJ->format('j F Y') }}</li>
                            @endif
                            <li>کمیسیون: {{ number_format($payout->commission_amount) }} تومان ({{ persianNumber($payout->commission_percent) }}٪)</li>
                            <li>ثبت: {{ $payout->persianCreatedAt('Y/m/d H:i') }}</li>
                            @if($order)
                                <li>وضعیت رزرو: {{ app(\App\Services\OrderShowPresenter::class)->displayStatusLabel($order, 'host') }}</li>
                            @endif
                            @if($payout->paid_at)
                                <li>پرداخت: {{ $payout->persianDate('paid_at', 'Y/m/d H:i') }}</li>
                            @endif
                            @if($payout->status === HostPayout::PAID && filled($payout->payment_reference))
                                <li>@lang('title.payment_reference'): {{ $payout->payment_reference }}</li>
                            @endif
                        </ul>

                        @if($order)
                            <a href="{{ route('dashboard.orders.show', $order->id) }}" class="host-payout-card__link">
                                مشاهده رزرو
                            </a>
                        @endif
                    </div>
                @endforeach

                <div class="text-center pt-2">
                    {{ $payouts->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-cash-stack fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">موردی در این بخش نیست</h5>
                </div>
            @endif
        </div>
    </div>
@endsection
