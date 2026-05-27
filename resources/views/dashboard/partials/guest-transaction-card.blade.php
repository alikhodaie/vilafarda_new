@php
    $order = $transaction->orders->first();
    $home = $order?->home;
    $checkinJ = $order?->checkin ? persianDate($order->checkin->copy()) : null;
    $checkoutCarbon = $order?->checkout ? $order->checkout->copy()->addDay() : null;
    $checkoutJ = $checkoutCarbon ? persianDate($checkoutCarbon) : null;
@endphp

<div class="guest-tx-card">
    <div class="guest-tx-card__header">
        <div>
            <h6 class="guest-tx-card__title mb-1">{{ $home?->name ?? $transaction->description }}</h6>
            @if($home?->code)
                <small class="text-muted">کد اقامتگاه: {{ $home->code }}</small>
            @endif
        </div>
        <span class="badge badge-soft-{{ $transaction->status('color') }}">{{ $transaction->status() }}</span>
    </div>

    <div class="guest-tx-card__amount">
        {{ number_format($transaction->price) }} @lang('title.toman')
    </div>

    <ul class="guest-tx-card__meta list-unstyled mb-0">
        <li>
            <span class="text-muted">درگاه:</span>
            {{ $transaction->gateway() }}
        </li>
        @if($transaction->reference)
            <li>
                <span class="text-muted">شماره پیگیری:</span>
                #{{ $transaction->reference }}
            </li>
        @endif
        @if($checkinJ && $checkoutJ)
            <li>
                <span class="text-muted">تاریخ اقامت:</span>
                {{ $checkinJ->format('j F Y') }} تا {{ $checkoutJ->format('j F Y') }}
            </li>
        @endif
        @if($order)
            <li>
                <span class="text-muted">وضعیت رزرو:</span>
                {{ app(\App\Services\OrderShowPresenter::class)->displayStatusLabel($order, 'renter') }}
            </li>
        @endif
        <li>
            <span class="text-muted">تاریخ تراکنش:</span>
            {{ $transaction->persianCreatedAt('Y/m/d H:i') }}
        </li>
    </ul>

    @if($order)
        <a href="{{ route('dashboard.rents.show', $order->id) }}" class="guest-tx-card__link">
            مشاهده جزئیات سفر
        </a>
    @endif
</div>
