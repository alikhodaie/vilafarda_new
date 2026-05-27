@php
    $order = $payout->order;
    $home = $order?->home;
    $renter = $order?->renter;
    $checkinJ = $order?->checkin ? persianDate($order->checkin->copy()) : null;
    $checkoutCarbon = $order?->checkout ? $order->checkout->copy()->addDay() : null;
    $checkoutJ = $checkoutCarbon ? persianDate($checkoutCarbon) : null;
@endphp

<div class="host-payout-card">
    <div class="host-payout-card__header">
        <div>
            <h6 class="host-payout-card__title mb-1">{{ $home?->name ?? '—' }}</h6>
            <small class="text-muted">کد اقامتگاه: {{ $home?->code ?? '—' }}</small>
        </div>
        <span class="badge badge-soft-{{ $payout->status('color') }}">{{ $payout->status() }}</span>
    </div>

    <div class="host-payout-card__amount">
        {{ number_format($payout->amount) }} @lang('title.toman')
    </div>

    <ul class="host-payout-card__meta list-unstyled mb-0">
        <li>
            <span class="text-muted">مهمان:</span>
            {{ $renter?->full_name ?? '—' }}
        </li>
        @if($checkinJ && $checkoutJ)
            <li>
                <span class="text-muted">تاریخ اقامت:</span>
                {{ $checkinJ->format('j F Y') }} تا {{ $checkoutJ->format('j F Y') }}
            </li>
        @endif
        <li>
            <span class="text-muted">مبلغ رزرو:</span>
            {{ number_format($payout->order_price) }} @lang('title.toman')
        </li>
        <li>
            <span class="text-muted">کمیسیون ({{ persianNumber($payout->commission_percent) }}٪):</span>
            {{ number_format($payout->commission_amount) }} @lang('title.toman')
        </li>
        <li>
            <span class="text-muted">ثبت:</span>
            {{ $payout->persianCreatedAt('Y/m/d H:i') }}
        </li>
        @if($order)
            <li>
                <span class="text-muted">وضعیت رزرو:</span>
                {{ app(\App\Services\OrderShowPresenter::class)->displayStatusLabel($order, 'host') }}
            </li>
        @endif
        @if($payout->paid_at)
            <li>
                <span class="text-muted">تاریخ پرداخت به میزبان:</span>
                {{ $payout->persianDate('paid_at', 'Y/m/d H:i') }}
            </li>
        @endif
        @if($payout->status === \App\Models\HostPayout::PAID && filled($payout->payment_reference))
            <li>
                <span class="text-muted">@lang('title.payment_reference'):</span>
                {{ $payout->payment_reference }}
            </li>
        @endif
    </ul>

    @if($order)
        <a href="{{ route('dashboard.orders.show', $order->id) }}" class="host-payout-card__link">
            مشاهده جزئیات رزرو
        </a>
    @endif
</div>
