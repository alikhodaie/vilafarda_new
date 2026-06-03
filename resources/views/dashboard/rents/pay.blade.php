@extends('layouts.dashboard.dashboard', [
    'title' => __('title.payment_checkout'),
    'active' => 'rents',
    'breadcrumbs' => [
        ['url' => route('dashboard.rents.index'), 'title' => __('title.rents')],
        ['url' => route('dashboard.rents.show', $rent), 'title' => __('title.rent_home', ['home' => $rent->home->name])],
        ['url' => null, 'title' => __('title.payment_checkout')],
    ],
])

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="mb-1">@lang('title.payment_checkout')</h4>
                    <p class="text-muted mb-4">{{ $rent->home->name }}</p>

                    @if($paymentDeadline)
                        @include('dashboard.partials.order-deadline-countdown', [
                            'deadline' => $paymentDeadline,
                            'label' => __('title.pay_limit'),
                        ])
                    @endif

                    <div class="border rounded-3 p-3 mb-4 bg-light">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">@lang('title.payable_amount')</span>
                            <strong>{{ number_format($rent->payable_price) }} @lang('title.toman')</strong>
                        </div>
                        @if($rent->discount)
                            <div class="d-flex justify-content-between small text-muted">
                                <span>@lang('title.discount')</span>
                                <span>{{ number_format($rent->discount) }} @lang('title.toman')</span>
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('dashboard.rents.pay', $rent) }}" method="POST">
                        @csrf
                        <p class="fw-semibold mb-3">@lang('title.choose_payment_gateway')</p>

                        <div class="d-flex flex-column gap-2 mb-4">
                            @foreach($gateways as $gateway)
                                <label class="payment-gateway-option border rounded-3 p-3 d-flex align-items-start gap-3 mb-0 cursor-pointer">
                                    <input class="form-check-input mt-1"
                                           type="radio"
                                           name="gateway"
                                           value="{{ $gateway['key'] }}"
                                           @if($loop->first) checked @endif
                                           required>
                                    <span class="flex-grow-1">
                                        <span class="d-flex align-items-center gap-2 fw-semibold">
                                            <i class="bi {{ $gateway['icon'] }} text-success"></i>
                                            {{ $gateway['text'] }}
                                        </span>
                                        <span class="d-block small text-muted mt-1">{{ $gateway['description'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        @error('gateway')
                            <div class="alert alert-danger py-2">{{ $message }}</div>
                        @enderror

                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-arrow-left-circle me-1"></i>
                                @lang('title.continue_to_payment')
                            </button>
                            <a href="{{ route('dashboard.rents.show', $rent) }}" class="btn btn-outline-secondary">
                                @lang('title.back')
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom-assets')
    <style>
        .payment-gateway-option:has(input:checked) {
            border-color: #2e9e5a !important;
            background: #f3fbf6;
        }
        .payment-gateway-option {
            cursor: pointer;
            transition: border-color 0.15s ease, background 0.15s ease;
        }
    </style>
@endpush
