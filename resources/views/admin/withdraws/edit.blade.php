@extends('layouts.admin.admin', ['title' => __('title.withdraws'), 'active' => 'withdraws'])

@section('content')
    @php
        $order = $withdraw->order;
        $home = $order?->home;
        $renter = $order?->renter;
    @endphp

    <x-admin.card title="{{ __('title.withdraws') }}">
        <form action="{{ route('admin.withdraws.update', $withdraw->id) }}" method="POST" class="p-3 row">
            @csrf
            @method('PUT')

            <div class="col-12 mb-4">
                <div class="row g-3">
                    <div class="col-md-4"><strong>میزبان:</strong> {{ $withdraw->user->full_name }}</div>
                    <div class="col-md-4"><strong>اقامتگاه:</strong> {{ $home?->name ?? '—' }}</div>
                    <div class="col-md-4">
                        <div><strong>@lang('title.shaba'):</strong> {{ $home?->shaba ? 'IR' . $home->shaba : '—' }}</div>
                        @if($home)
                            <a href="{{ route('admin.homes.edit', ['home' => $home, 'open_tab' => 'tab-admin']) }}"
                               class="btn btn-sm btn-falcon-primary mt-2">
                                <span class="fas fa-edit ms-1"></span>
                                ویرایش اطلاعات حساب
                            </a>
                        @endif
                    </div>
                    <div class="col-md-4"><strong>مهمان:</strong> {{ $renter?->full_name ?? '—' }}</div>
                    <div class="col-md-4"><strong>مبلغ رزرو:</strong> {{ number_format($withdraw->order_price) }} تومان</div>
                    <div class="col-md-4"><strong>کمیسیون:</strong> {{ number_format($withdraw->commission_amount) }} تومان ({{ $withdraw->commission_percent }}٪)</div>
                    <div class="col-md-4"><strong>مبلغ تسویه:</strong> {{ number_format($withdraw->amount) }} تومان</div>
                    @if($order)
                        <div class="col-md-4"><strong>ورود:</strong> {{ $order->persianDate('start_at', 'Y/m/d') }}</div>
                        <div class="col-md-4"><strong>خروج:</strong> {{ $order->persianDate('end_at', 'Y/m/d') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-md-6 mb-4">
                <label class="form-label" for="status">@lang('title.status') <span>*</span></label>
                <select name="status" id="status" class="form-control">
                    <option value="">@lang('title.select')</option>
                    @foreach(\App\Models\HostPayout::STATUSES as $status)
                        <option @if($status['value'] === old('status', $withdraw->status)) selected @endif value="{{ $status['value'] }}">{{ $status['fa_text'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-6 mb-4">
                <label class="form-label" for="payment_reference">@lang('title.payment_reference')</label>
                <input type="text"
                       class="form-control"
                       name="payment_reference"
                       id="payment_reference"
                       maxlength="100"
                       value="{{ old('payment_reference', $withdraw->payment_reference) }}"
                       placeholder="اختیاری — شناسه واریز بانکی">
                <p class="text-muted small mb-0 mt-1">در صورت ثبت، در تراکنش‌های میزبان (تسویه‌های پرداخت‌شده) نمایش داده می‌شود.</p>
            </div>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.edit')</button>
                <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                <a href="{{ route('admin.withdraws.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection
