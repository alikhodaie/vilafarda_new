@php
    $class = $class ?? 'col-12';
    $presenter = app(\App\Services\OrderShowPresenter::class);
    $canPay = $presenter->canRenterPay($rent);
    $canCancel = $presenter->canRenterCancel($rent);
@endphp

@if($canPay || $canCancel)
    <div class="{{ $class }}">
        @if($canPay)
            <div class="border rounded-4 shadow-sm p-3 mb-3 text-center">
                <div class="text-muted fw-bold mb-2" style="font-size: 0.95rem;">
                    <i class="fa fa-clock me-1"></i> @lang('title.pay_limit')
                </div>
                <count-down-timer
                    time="{{ $rent->getPaymentDeadline()?->toIso8601String() }}"
                    prop_now="{{ now()->toIso8601String() }}"
                    color="#dc3545"
                    text="@lang('title.pay_limit')"
                    text_expired="@lang('text.time_expired')"
                />
            </div>

            <rent-discount
                discount_route="{{ route('dashboard.rents.discount', $rent) }}"
                :has_discount="{{ $rent->discount ? 'true' : 'false' }}"
                :original_price="{{ $rent->price }}"
                :discount_amount="{{ $rent->discount }}"
            ></rent-discount>
        @endif

        <div class="row g-2">
            @if($canPay)
                <div class="col-6">
                    <form method="post" action="{{ route('dashboard.rents.pay', $rent) }}">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-credit-card-2-front me-1"></i> @lang('title.pay')
                        </button>
                    </form>
                </div>
            @endif
            @if($canCancel)
                <div class="{{ $canPay ? 'col-6' : 'col-12' }}">
                    <change-status
                        route="{{ route('dashboard.rents.cancel', $rent) }}"
                        csrf="{{ csrf_token() }}"
                        title="@lang('title.reserve_cancel')"
                        button_text="@lang('title.reserve_cancel')"
                        class="w-100"
                        button_class="btn btn-danger w-100"
                        text="@lang('text.cancel_reserve')"
                        button_cancel_text="@lang('title.cancel')"
                        button_submit_text="@lang('title.reserve_cancel')"
                    ></change-status>
                </div>
            @endif
        </div>
    </div>
@endif
