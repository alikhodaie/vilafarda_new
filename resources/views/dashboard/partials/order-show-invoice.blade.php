@php
    $entity = $order ?? $rent;
    $displayPrice = $entity->discount ? $entity->payable_price : $entity->price;
@endphp

<div class="rent-invoice-card">
    <h3 class="rent-invoice-card__title">
        <i class="bi bi-receipt" aria-hidden="true"></i>
        صورتحساب
    </h3>

    <p class="rent-invoice-card__section-label">
        اقامت ({{ persianNumber($invoice['nights']) }} شب)
    </p>
    <ul class="rent-invoice-card__lines">
        @foreach($invoice['night_lines'] as $line)
            <li class="rent-invoice-card__line">
                <div class="rent-invoice-card__line-info">
                    <span class="rent-invoice-card__line-label">{{ $line['label'] }}</span>
                    @if(!empty($line['hint']))
                        <span class="rent-invoice-card__line-hint">{{ $line['hint'] }}</span>
                    @endif
                </div>
                <span class="rent-invoice-card__line-amount">{{ persianNumber($line['amount']) }} تومان</span>
            </li>
        @endforeach
    </ul>

    <ul class="rent-invoice-card__summary">
        <li>
            <span>جمع قیمت شب‌ها ({{ persianNumber($invoice['nights']) }} شب)</span>
            <strong>{{ persianNumber($invoice['nights_subtotal']) }} تومان</strong>
        </li>

        @if($invoice['extra_guest_total'] > 0)
            <li>
                <span>
                    هزینه {{ persianNumber($invoice['extra_guest']) }} نفر اضافه
                    ({{ persianNumber($invoice['nights']) }} شب × {{ persianNumber($invoice['extra_guest_unit']) }} تومان)
                </span>
                <strong>+{{ persianNumber($invoice['extra_guest_total']) }} تومان</strong>
            </li>
        @endif

        @if($invoice['extra_guest_total'] > 0 || $invoice['stay_discount'] > 0)
            <li>
                <span>جمع قبل از تخفیف اقامت</span>
                <strong>{{ persianNumber($invoice['subtotal_before_discount']) }} تومان</strong>
            </li>
        @endif

        @if($invoice['stay_discount'] > 0)
            <li class="rent-invoice-card__line--discount">
                <span>{{ $invoice['stay_discount_label'] }}</span>
                <strong>−{{ persianNumber($invoice['stay_discount']) }} تومان</strong>
            </li>
        @endif

        <li class="rent-invoice-card__summary--total">
            <span>جمع کل رزرو</span>
            <strong>{{ persianNumber($invoice['total']) }} تومان</strong>
        </li>

        @if(!empty($hostSettlement))
            @if($hostSettlement['commission_percent'] > 0)
                <li>
                    <span>
                        کمیسیون سایت
                        @if($hostSettlement['policy_title'])
                            (سیاست لغو {{ $hostSettlement['policy_title'] }} — {{ persianNumber($hostSettlement['commission_percent']) }}٪)
                        @else
                            ({{ persianNumber($hostSettlement['commission_percent']) }}٪)
                        @endif
                    </span>
                    <strong>−{{ persianNumber($hostSettlement['commission_amount']) }} تومان</strong>
                </li>
            @endif
            <li class="rent-invoice-card__summary--host-payout">
                <span>
                    مبلغ تسویه میزبان
                    @if($hostSettlement['policy_title'])
                        <span class="rent-invoice-card__host-payout-hint">طبق سیاست لغو «{{ $hostSettlement['policy_title'] }}»</span>
                    @endif
                </span>
                <strong>{{ persianNumber($hostSettlement['payout_amount']) }} تومان</strong>
            </li>
        @endif

        @if($invoice['coupon_discount'] > 0)
            <li class="rent-invoice-card__line--discount">
                <span>
                    تخفیف کد
                    @if($invoice['coupon_code'])
                        ({{ $invoice['coupon_code'] }})
                    @endif
                </span>
                <strong>−{{ persianNumber($invoice['coupon_discount']) }} تومان</strong>
            </li>
            <li class="rent-invoice-card__summary--payable">
                <span>مبلغ قابل پرداخت مهمان</span>
                <strong>{{ persianNumber($invoice['payable']) }} تومان</strong>
            </li>
        @endif
    </ul>
</div>
