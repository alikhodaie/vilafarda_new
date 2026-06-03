@extends('layouts.main.main_mobile', ['title' => __('title.payment_checkout')])

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/rent-pay-mobile.css') }}">
@endsection

@section('content')
    @include('layouts.main.partials.navbar-mobile')

    <div class="rent-pay-page">
        <div class="rent-pay-page__header">
            <a href="{{ route('dashboard.rents.show', $rent) }}" class="rent-pay-page__back" aria-label="@lang('title.back')">
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
            <h1 class="rent-pay-page__title">@lang('title.payment_checkout')</h1>
        </div>

        <div class="rent-pay-page__home">
            <p class="rent-pay-page__home-name mb-0">{{ $rent->home->name }}</p>
            <p class="rent-pay-page__home-meta mb-0">{{ $rent->home->province->name }}، {{ $rent->home->city->name }}</p>
        </div>

        @if($paymentDeadline)
            <div class="rent-pay-page__deadline">
                @include('dashboard.partials.order-deadline-countdown', [
                    'deadline' => $paymentDeadline,
                    'label' => __('title.pay_limit'),
                ])
            </div>
        @endif

        <div class="rent-pay-page__amount">
            <span class="rent-pay-page__amount-label">@lang('title.payable_amount')</span>
            <span class="rent-pay-page__amount-value">{{ number_format($rent->payable_price) }} @lang('title.toman')</span>
        </div>

        <form action="{{ route('dashboard.rents.pay', $rent) }}" method="POST" class="rent-pay-page__form">
            @csrf

            <p class="rent-pay-page__section-title">@lang('title.choose_payment_gateway')</p>

            <div class="rent-pay-page__gateways">
                @foreach($gateways as $gateway)
                    <label class="rent-pay-gateway">
                        <input type="radio"
                               name="gateway"
                               value="{{ $gateway['key'] }}"
                               @if($loop->first) checked @endif
                               required>
                        <span class="rent-pay-gateway__body">
                            <span class="rent-pay-gateway__icon">
                                <i class="bi {{ $gateway['icon'] }}" aria-hidden="true"></i>
                            </span>
                            <span class="rent-pay-gateway__text">
                                <span class="rent-pay-gateway__title">{{ $gateway['text'] }}</span>
                                <span class="rent-pay-gateway__desc">{{ $gateway['description'] }}</span>
                            </span>
                            <span class="rent-pay-gateway__check" aria-hidden="true"></span>
                        </span>
                    </label>
                @endforeach
            </div>

            @error('gateway')
                <p class="rent-pay-page__error">{{ $message }}</p>
            @enderror

            <button type="submit" class="rent-pay-page__submit">
                @lang('title.continue_to_payment')
            </button>
        </form>
    </div>
@endsection
