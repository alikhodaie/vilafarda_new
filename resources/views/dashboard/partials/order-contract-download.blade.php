@php($orderModel = $order ?? $rent ?? null)
@if(($canDownloadContract ?? false) && $orderModel)
    <div class="rent-voucher-card">
        <h3 class="rent-voucher-card__title">
            <i class="bi bi-ticket-perforated" aria-hidden="true"></i>
            @lang('title.voucher')
        </h3>
        <p class="rent-voucher-card__text">
            واچر رزرو پس از پرداخت تا تاریخ خروج قابل دانلود است.
        </p>
        <a href="{{ $contractRoute }}"
           class="rent-status-card__btn rent-status-card__btn--pay rent-voucher-card__btn w-100 text-decoration-none">
            <i class="bi bi-download" aria-hidden="true"></i>
            @lang('title.download_voucher')
        </a>
    </div>
@endif
