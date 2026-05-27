@extends('layouts.dashboard.dashboard-mobile', [
    'title' => __('title.rents'),
    'active' => 'rents',
    'breadcrumbs' => [['url' => null, 'title' => __('title.rents')]]
])

@section('content')
    <div>
        {{-- فیلتر وضعیت --}}
        <div class="mb-4">
            <form action="{{ route('dashboard.rents.index') }}" class="d-flex gap-2">
                <select name="status" id="status"
                        class="form-select form-select-sm flex-grow-1"
                        style="background-color: #CACACA; color: #343434; border-radius: 0.75rem;">
                    <option value="">@lang('title.select') @lang('title.status')</option>
                    @foreach(\App\Models\Order::STATUSES as $status)
                        <option value="{{ $status['value'] }}" @if ($status['value'] === request('status')) selected @endif>
                            {{ $status['fa_text'] }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                        class="btn"
                        style="background-color: #E6B31E; color: #343434; border-radius: 0.75rem;">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        {{-- لیست رنت‌ها --}}
        @forelse($rents as $rent)
            <div class="card mb-4 border-0 rounded-4 overflow-hidden shadow-sm"
                 style="box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05), 0 2px 4px rgba(0, 0, 0, 0.03); transition: all 0.3s ease;">
                
                {{-- تصویر خانه --}}
                <a href="{{ $rent->home->link }}" target="_blank" class="d-block text-decoration-none">
                    <!-- <img src="{{ $rent->home->cover_path }}"
                         class="w-100"
                         style="height: 200px; object-fit: cover;"
                         alt="{{ $rent->home->name }}"> -->
                    <img src="https://cf.bstatic.com/xdata/images/hotel/max1024x768/698156760.jpg?k=67e1be27243fedc6c6562a7b47493c98e0cb1fdd692cd9ef81f57fa1e665f16a&o="
                         class="w-100"
                         style="height: 200px; object-fit: cover;"
                        >
                </a>

                <div class="card-body">
                    {{-- نام و موقعیت --}}
                    <h5 class="fw-bold text-center mb-1" style="color: #343434;">{{ $rent->home->name }}</h5>
                    <p class="text-center text-muted small mb-3">
                        <i class="fa fa-map-marker-alt me-1"></i>
                        {{ $rent->home->province->name }}، {{ $rent->home->city->name }}
                    </p>

                    {{-- وضعیت پرداخت --}}
                    <div class="mb-3">
                        @if($rent->paid_at)
                            <span class="badge bg-success">@lang('title.paid')</span>
                        @endif
                    </div>

                    {{-- اطلاعات --}}
                    <ul class="list-unstyled small mb-3 p-0" style="color:#666;">
                        <li class="mb-1">
                            <strong>@lang('title.status'):</strong>
                            <span class="badge bg-{{ app(\App\Services\OrderShowPresenter::class)->displayStatusColor($rent) }}">{{ app(\App\Services\OrderShowPresenter::class)->displayStatusLabel($rent, 'renter') }}</span>
                        </li>
                        <li class="mb-1">
                            <i class="fa fa-user me-1"></i>
                            {{ number_format($rent->main_guest) }} @lang('title.person')
                            @if($rent->extra_guest)
                                + {{ number_format($rent->extra_guest) }} @lang('title.extra')
                            @endif
                        </li>
                        <li class="mb-1">
                            <i class="fa fa-money-bill me-1"></i>
                            @if($rent->discount)
                                <span style="text-decoration: line-through; color: #999;">{{ number_format($rent->price) }}</span>
                                {{ number_format($rent->payable_price) }}
                            @else
                                {{ number_format($rent->price) }}
                            @endif
                            @lang('title.toman')
                        </li>
                        <li class="mb-1">
                            <i class="fa fa-calendar-plus me-1"></i> {{ $rent->start_date }}
                        </li>
                        <li class="mb-1">
                            <i class="fa fa-calendar-minus me-1"></i> {{ $rent->end_date }}
                        </li>
                    </ul>

                    {{-- دکمه‌ها --}}
                    <div>
                        @include('dashboard.rents.partials.actions', ['rent' => $rent])

                        @if(app(\App\Services\OrderShowPresenter::class)->canDownloadContract($rent))
                            <a href="{{ route('dashboard.rents.contract', $rent) }}"
                               class="btn btn-success w-100 mt-3">
                                <i class="bi bi-download me-1"></i>
                                @lang('title.download_voucher')
                            </a>
                        @endif

                        <a href="{{ route('dashboard.rents.show', $rent) }}"
                           class="btn btn-outline-dark w-100 mt-2"
                           style="border-color: #343434; color: #343434;">
                            @lang('title.details')
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-warning">@lang('title.nothing found')</div>
        @endforelse

        {{-- صفحه‌بندی --}}
        <div class="my-4">
            {{ $rents->links() }}
        </div>
    </div>
@endsection
