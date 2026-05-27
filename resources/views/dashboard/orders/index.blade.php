@php use App\Models\Order; @endphp
@extends('layouts.dashboard.dashboard', ['title' => __('title.rent_requests'), 'active' => 'rent-requests', 'breadcrumbs' => [
    ['url' => null, 'title' => __('title.rent_requests')]
]])

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="_prt_filt_dash">
                <div class="_prt_filt_dash_flex">
                    <div class="foot-news-last">
                        <form action="{{ route('dashboard.orders.index') }}" class="row">
                            <div class="col-11 form-group">
                                <select name="status" id="status" class="form-control">
                                    <option value="">@lang('title.select') @lang('title.status')</option>
                                    @foreach(Order::STATUSES as $status)
                                        <option value="{{ $status['value'] }}"
                                                @if ($status['value'] === request('status')) selected @endif>{{ $status['fa_text'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-1 form-group">
                                <button type="submit" class="input-group-text theme-bg b-0 text-light"
                                        style="height: 100%">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            @if($orders->isNotEmpty())
                @foreach($orders as $order)
                    <div class="card">
                        <div class="row @if(! $loop->first) mt-5 @endif">
                            <div class="col-12 col-md-4 mb-3">
                                <a href="{{ route('dashboard.homes.index', ['name' => $order->home->name]) }}"
                                   target="_blank">
                                    <img class="w-100" src="{{ $order->home->cover_path }}"
                                         alt="{{ $order->home->name }}">
                                    <div class="text-center">
                                        <h5>{{ $order->home->name }}</h5>
                                        <div class="prt_dashb_lot"><i
                                                class="fa fa-map-marker-alt"></i> {{ $order->home->province->name }}
                                            ، {{ $order->home->city->name }}</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-12 col-md-8 mb-3 p-4 p-md-5">
                                @if($order->paid_at)
                                    <div class="my-3"><span class="badge badge-success">@lang('title.paid')</span></div>
                                @endif
                                @if(app(\App\Services\OrderShowPresenter::class)->canHostRespondToPending($order))
                                    @include('dashboard.partials.order-deadline-countdown', [
                                        'deadline' => $order->getPendingDeadline(),
                                        'label' => __('title.status_determination_deadline'),
                                    ])
                                @elseif($order->status === \App\Models\Order::AWAITING_PAYMENT && ! $order->isPaymentDeadlinePassed())
                                    @include('dashboard.partials.order-deadline-countdown', [
                                        'deadline' => $order->getPaymentDeadline(),
                                        'label' => 'مهلت پرداخت مهمان',
                                    ])
                                @endif
                                <div class="my-3">@lang('title.status'): <span
                                        class="badge badge-{{ app(\App\Services\OrderShowPresenter::class)->displayStatusColor($order) }}">{{ app(\App\Services\OrderShowPresenter::class)->displayStatusLabel($order, 'host') }}</span>
                                </div>
                                <div class="my-3"><i class="fa fa-user"></i> @lang('title.guest_count')
                                    : {{ number_format($order->main_guest) }} @lang('title.person') @if($order->extra_guest)
                                        + {{ number_format($order->extra_guest) }} @lang('title.extra')
                                    @endif</div>
                                <div class="my-3"><i class="fa fa-money-bill"></i> @lang('title.price')
                                    : {{ number_format($order->price) }} @lang('title.toman')</div>
                                <div class="my-3"><i class="fa fa-calendar-plus"></i> {{ $order->start_date }}</div>
                                <div class="my-3"><i class="fa fa-calendar-minus"></i> {{ $order->end_date }}</div>

                                <div class="row">
                                    @include('dashboard.orders.partials.actions', ['order' => $order])
                                    @if(app(\App\Services\OrderShowPresenter::class)->canDownloadContract($order))
                                        <div class="col-12 col-md-3 mb-3 mb-md-0">
                                            <a href="{{ route('dashboard.orders.contract', $order) }}"
                                               class="w-100 btn btn-success">
                                                <i class="bi bi-download me-1"></i>
                                                @lang('title.download_voucher')
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{ $orders->links() }}
            @else
                <div class="alert alert-warning">
                    @lang('title.nothing found')
                </div>
            @endif
        </div>
    </div>
@endsection
