@extends('layouts.dashboard.dashboard', ['title' => __('title.rent_home', ['home' => $rent->home->name]), 'active' => 'rents', 'breadcrumbs' => [
    ['url' => route('dashboard.rents.index'), 'title' => __('title.rents')],
    ['url' => null, 'title' => __('title.rent_home', ['home' => $rent->home->name])]
]])

@section('content')
    <div class="row">
        <div class="col-12 col-md-4 mb-3">
            <a href="{{ $rent->home->link }}" target="_blank">
                <img class="w-100" src="{{ $rent->home->cover_path }}" alt="{{ $rent->home->name }}">
                <div class="text-center mt-4">
                    <h5>{{ $rent->home->name }}</h5>
                    <div class="prt_dashb_lot"><i class="fa fa-map-marker-alt"></i> {{ $rent->home->province->name }}، {{ $rent->home->city->name }}</div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-8 mb-3 p-0 p-md-5">
            @if($rent->paid_at)
                <div class="my-3"><span class="badge badge-success">@lang('title.paid')</span></div>
            @endif
            <div class="my-3">@lang('title.status'): <span class="badge badge-{{ app(\App\Services\OrderShowPresenter::class)->displayStatusColor($rent) }}">{{ app(\App\Services\OrderShowPresenter::class)->displayStatusLabel($rent, 'renter') }}</span></div>
            <div class="my-3"><i class="fa fa-user"></i> @lang('title.guest_count'): {{ number_format($rent->main_guest) }} @lang('title.person') @if($rent->extra_guest) + {{ number_format($rent->extra_guest) }} @lang('title.extra') @endif</div>
            <div class="my-3"><i class="fa fa-money-bill"></i> @lang('title.price'):
                @if($rent->discount)
                    <span style="text-decoration: line-through; color: red; font-size: 14px">{{ number_format($rent->price) }}</span>
                    {{ number_format($rent->payable_price) }}
                @else
                    {{ number_format($rent->price) }}
                @endif
                @lang('title.toman')
            </div>
            <div class="my-3"><i class="fa fa-calendar-plus"></i> {{ $rent->start_date }}</div>
            <div class="my-3"><i class="fa fa-calendar-minus"></i> {{ $rent->end_date }}</div>

            @if($rent->status === \App\Models\Order::PENDING)
                @include('dashboard.partials.order-deadline-countdown', [
                    'deadline' => $rent->getPendingDeadline(),
                    'label' => 'مهلت تأیید میزبان',
                ])
            @elseif($rent->status === \App\Models\Order::AWAITING_PAYMENT)
                @include('dashboard.partials.order-deadline-countdown', [
                    'deadline' => $rent->getPaymentDeadline(),
                    'label' => 'مهلت پرداخت',
                ])
            @endif

            <div class="row">
                @include('dashboard.rents.partials.actions', compact('rent'))

                @if(app(\App\Services\OrderShowPresenter::class)->canDownloadContract($rent))
                    <div class="col-12 col-md-3 mb-3 mb-md-0">
                        <a href="{{ route('dashboard.rents.contract', $rent) }}" class="w-100 btn btn-success">
                            <i class="bi bi-download me-1"></i>
                            @lang('title.download_voucher')
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-12">
            @if($rent->canShowMap())
                <leaftlet-map :readonly="true" :height="350" :latitude="{{ $rent->home->latitude }}" :longitude="{{ $rent->home->longitude }}"></leaftlet-map>
            @else
                <div style="height: 350px" class="alert alert-info">
                    <div style="height: 100%" class="d-flex justify-content-center"><span style="font-size: 25px;" class="align-self-center">@lang('text.place_show_map')</span></div>
                </div>
            @endif
        </div>
    </div>
@endsection
