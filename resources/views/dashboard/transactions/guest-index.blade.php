@extends('layouts.dashboard.dashboard', ['title' => __('title.guest_transactions'), 'active' => 'guest-transactions', 'breadcrumbs' => [
    ['url' => null, 'title' => __('title.guest_transactions')]
]])

@section('styles')
    <style>
        .guest-tx-card {
            border: 1px solid #e3e6ec;
            border-radius: 0.5rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            background: #fff;
        }
        .guest-tx-card__header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }
        .guest-tx-card__title { font-size: 1rem; font-weight: 700; }
        .guest-tx-card__amount {
            font-size: 1.25rem;
            font-weight: 700;
            color: #198754;
            margin-bottom: 0.75rem;
        }
        .guest-tx-card__meta li { margin-bottom: 0.35rem; font-size: 0.9rem; }
        .guest-tx-card__link {
            display: inline-block;
            margin-top: 0.75rem;
            font-size: 0.875rem;
        }
    </style>
@endsection

@section('content')
    <h3 class="mb-3">@lang('title.guest_transactions')</h3>

    <div class="alert alert-info border-2 d-flex align-items-center mb-4" role="alert">
        <div class="bg-info ml-3 icon-item rounded-circle">
            <span class="fas fa-info-circle text-white fa-2x m-2"></span>
        </div>
        <p class="mb-0 flex-1">پرداخت‌های شما برای رزرو اقامتگاه‌ها در این بخش نمایش داده می‌شود.</p>
    </div>

    @if($transactions->isNotEmpty())
        @foreach($transactions as $transaction)
            @include('dashboard.partials.guest-transaction-card', ['transaction' => $transaction])
        @endforeach

        <div class="text-center pt-3">
            {{ $transactions->links() }}
        </div>
    @else
        <div class="alert alert-warning">
            @lang('title.nothing found')
        </div>
    @endif
@endsection
