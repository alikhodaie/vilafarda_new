@extends('layouts.dashboard.dashboard', ['title' => __('title.host_transactions'), 'active' => 'host-transactions', 'breadcrumbs' => [
    ['url' => null, 'title' => __('title.host_transactions')]
]])

@section('styles')
    <style>
        .host-payout-tabs .nav-link { font-weight: 600; }
        .host-payout-card {
            border: 1px solid #e3e6ec;
            border-radius: 0.5rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            background: #fff;
        }
        .host-payout-card__header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }
        .host-payout-card__title { font-size: 1rem; font-weight: 700; }
        .host-payout-card__amount {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2c7be5;
            margin-bottom: 0.75rem;
        }
        .host-payout-card__meta li { margin-bottom: 0.35rem; font-size: 0.9rem; }
        .host-payout-card__link {
            display: inline-block;
            margin-top: 0.75rem;
            font-size: 0.875rem;
        }
    </style>
@endsection

@section('content')
    @php
        use App\Models\HostPayout;

        $tabs = [
            HostPayout::TAB_PENDING => 'در انتظار پرداخت',
            HostPayout::TAB_PAID => 'پرداخت شده',
            HostPayout::TAB_CANCELLED => 'لغو شده',
        ];
    @endphp

    <h3 class="mb-3">@lang('title.host_transactions')</h3>

    <div class="alert alert-info border-2 d-flex align-items-center mb-4" role="alert">
        <div class="bg-info ml-3 icon-item rounded-circle">
            <span class="fas fa-info-circle text-white fa-2x m-2"></span>
        </div>
        <p class="mb-0 flex-1">پس از پرداخت مهمان، مبلغ تسویه هر رزرو به‌صورت جداگانه در این بخش نمایش داده می‌شود.</p>
    </div>

    <ul class="nav nav-tabs host-payout-tabs mb-4">
        @foreach($tabs as $tabKey => $tabLabel)
            <li class="nav-item">
                <a class="nav-link @if($tab === $tabKey) active @endif"
                   href="{{ route('dashboard.host-transactions.index', ['tab' => $tabKey]) }}">
                    {{ $tabLabel }} ({{ persianNumber($counts[$tabKey] ?? 0) }})
                </a>
            </li>
        @endforeach
    </ul>

    @if($payouts->isNotEmpty())
        @foreach($payouts as $payout)
            @include('dashboard.partials.host-payout-card', ['payout' => $payout])
        @endforeach

        <div class="text-center pt-3">
            {{ $payouts->links() }}
        </div>
    @else
        <div class="alert alert-warning">
            @lang('title.nothing found')
        </div>
    @endif
@endsection
