@php use App\Models\Order; @endphp
@extends('layouts.admin.admin', ['title' => __('title.show_order'), 'active' => 'orders'])

@section('content')
    <x-admin.card title="{{ __('title.show_order') }}">
        @can('updateStatus', $order)
            <form action="{{ route('admin.orders.update.status', $order) }}" method="POST" class="p-3 row">
                @csrf
                @method('PATCH')

                <div class="col-12 mb-3">
                    <label for="status">@lang('title.status')</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">@lang('title.select')</option>
                        @foreach(Order::STATUSES as $status)
                            <option value="{{ $status['value'] }}"
                                    @if($status['value'] == $order->status) selected @endif>{{ $status['fa_text'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 mt-5 d-flex justify-content-center">
                    <button class="btn btn-falcon-success">@lang('title.edit')</button>
                </div>
            </form>
        @endcan
        <div class="p-3 row">
            <div class="col-12 col-md-3 mb-3">
                <label for="home">@lang('title.home'):</label>
                <strong id="home">{{ $order->home->name }}</strong>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label for="request_date">@lang('title.request_date'):</label>
                <strong id="request_date">{{ $order->persianCreatedAt() }}</strong>
            </div>

            <div class="col-12 col-md-3 mb-3">
                <label for="price">@lang('title.price'):</label>
                <strong id="price">{{ number_format($order->price) }}</strong>
            </div>

            <div class="col-12 col-md-3 mb-3">
                <label for="discount">@lang('title.discount'):</label>
                <strong id="discount">{{ number_format($order->discount) }}</strong>
            </div>

            <div class="col-12 col-md-3 mb-3">
                <label for="discount_code">@lang('title.discount code'):</label>
                <strong id="discount_code">{{ $order->discountModel?->code ?? '-' }}</strong>
            </div>

            <div class="col-12 col-md-3 mb-3">
                <label for="owner">@lang('title.owner'):</label>
                <strong id="owner">{{ $order->owner->full_name }}</strong>
            </div>

            <div class="col-12 col-md-3 mb-3">
                <label for="guest">@lang('title.guest'):</label>
                <strong id="guest">{{ $order->renter->full_name }}</strong>
            </div>

            <div class="col-12 col-md-3 mb-3">
                <label for="main_guest_count">@lang('title.main_guest_count'):</label>
                <strong id="main_guest_count">{{ number_format($order->main_guest) }}</strong>
            </div>

            <div class="col-12 col-md-3 mb-3">
                <label for="extra_guest_count">@lang('title.extra_guest_count'):</label>
                <strong id="extra_guest_count">{{ number_format($order->extra_guest) }}</strong>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label for="accepted_at">@lang('title.accepted_date_request'):</label>
                <strong id="accepted_at">{{ ($order->accepted_at) ? $order->persianDate('accepted_at'): '-' }}</strong>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label for="paid_at">@lang('title.paid_date'):</label>
                <strong id="paid_at">{{ ($order->paid_at) ? $order->persianDate('paid_at'): '-' }}</strong>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label for="rent_date">@lang('title.rent_date'):</label>
                <strong id="rent_date">{{ $order->period_text }}</strong>
            </div>

            @can('showContract', $order)
                <div class="col-12 col-md-6 mb-3">
                    <a href="{{ route('admin.orders.contract', $order) }}"
                       class="btn btn-success">@lang('title.download') {{ $order->contract_text }}</a>
                </div>
            @endcan

            <div class="col-12 mt-5 d-flex justify-content-center">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </div>
    </x-admin.card>
@endsection
