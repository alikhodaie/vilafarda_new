@extends('layouts.main.main_mobile', ['title' => __('title.rent_requests')])

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/trips-mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/rent-show-mobile.css') }}">
@endsection

@section('content')
    @include('layouts.main.partials.navbar-mobile')

    @php
        use App\Models\Order;

        $tabs = [
            Order::HOST_ORDER_TAB_CURRENT => [
                'label' => 'جاری',
                'empty_title' => 'درخواست جاری ندارید',
                'empty_desc' => 'درخواست‌های رزرو جدید اینجا نمایش داده می‌شوند',
            ],
            Order::HOST_ORDER_TAB_FINISHED => [
                'label' => 'پایان‌یافته',
                'empty_title' => 'رزرو پایان‌یافته‌ای ندارید',
                'empty_desc' => 'رزروهای تکمیل‌شده در این بخش قرار می‌گیرند',
            ],
            Order::HOST_ORDER_TAB_UNSUCCESSFUL => [
                'label' => 'ناموفق',
                'empty_title' => 'رزرو ناموفقی ندارید',
                'empty_desc' => 'درخواست‌های رد یا لغو شده اینجا نمایش داده می‌شوند',
            ],
        ];
        $activeTab = $tabs[$tab] ?? $tabs[Order::HOST_ORDER_TAB_CURRENT];

        $sortOptions = [
            'checkin' => 'تاریخ ورود',
            'checkin_desc' => 'تاریخ ورود (جدیدتر)',
            'newest' => 'جدیدترین درخواست',
            'price_desc' => 'بیشترین مبلغ',
            'price_asc' => 'کمترین مبلغ',
        ];
    @endphp

    <div class="trips-page">
        <div class="trips-header">
            <div class="trips-breadcrumb">
                <a href="{{ route('dashboard.index') }}">داشبورد</a>
                <span class="mx-1">›</span>
                <span>درخواست‌های رزرو</span>
            </div>

            <nav class="trips-tabs">
                @foreach($tabs as $tabKey => $tabInfo)
                    <a href="{{ route('dashboard.orders.index', ['sort' => $sort, 'tab' => $tabKey]) }}"
                       class="trips-tab {{ $tab === $tabKey ? 'active' : '' }}">
                        {{ $tabInfo['label'] }} ({{ persianNumber($counts[$tabKey] ?? 0) }})
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="trips-content">
            @if($orders->isNotEmpty())
                <div class="trips-toolbar">
                    <span class="trips-toolbar__label">مرتب‌سازی بر اساس:</span>
                    <form method="GET" action="{{ route('dashboard.orders.index') }}" class="trips-toolbar__sort-form">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <select name="sort" class="trips-toolbar__select" onchange="this.form.submit()">
                            @foreach($sortOptions as $sortKey => $sortLabel)
                                <option value="{{ $sortKey }}" @if($sort === $sortKey) selected @endif>{{ $sortLabel }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                @foreach($orders as $order)
                    @continue(!$order->home)

                    @php
                        $checkinJ = $order->checkin ? persianDate($order->checkin->copy()) : null;
                        $checkoutCarbon = $order->checkout ? $order->checkout->copy()->addDay() : null;
                        $checkoutJ = $checkoutCarbon ? persianDate($checkoutCarbon) : null;
                        $displayPrice = $order->discount ? $order->payable_price : $order->price;
                        $renter = $order->renter;
                        $renterName = $renter?->first_name ?: ($renter?->full_name ?? 'مهمان');
                        $locationLine = trim(($order->home?->province?->name ?? '').' - '.($order->home?->city?->name ?? ''), ' -');
                        $nights = max(1, (int) $order->count_days);
                        $orderPresenter = app(\App\Services\OrderShowPresenter::class);

                        if ($tab === Order::HOST_ORDER_TAB_FINISHED) {
                            $badgeText = $order->status === Order::DONE ? 'تکمیل شد' : 'خاتمه یافته';
                            $badgeClass = 'trip-card__badge';
                        } else {
                            $badgeText = $orderPresenter->displayStatusLabel($order, 'host');
                            $badgeClass = match ($orderPresenter->displayStatusColor($order)) {
                                'danger' => 'trip-card__badge trip-card__badge--danger',
                                'warning' => 'trip-card__badge trip-card__badge--warning',
                                'success' => 'trip-card__badge',
                                default => 'trip-card__badge trip-card__badge--info',
                            };
                        }
                    @endphp

                    <article class="trip-card">
                        <div class="trip-card__media">
                            <img src="{{ $order->home?->cover_path }}"
                                 class="trip-card__img"
                                 alt="{{ $order->home?->name ?? '' }}"
                                 loading="lazy"
                                 decoding="async">

                            <span class="{{ $badgeClass }}">
                                @if($tab === Order::HOST_ORDER_TAB_FINISHED || in_array($order->status, [Order::DONE, Order::IN_RENT, Order::WAITING_FOR_RENTER]))
                                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                                @endif
                                {{ $badgeText }}
                            </span>

                            <span class="trip-card__code">کد رزرو: {{ persianNumber($order->id) }}</span>

                            <div class="trip-card__gradient" aria-hidden="true"></div>

                            @if($locationLine)
                                <p class="trip-card__subtitle-on-image">{{ $order->home?->name }} — {{ $locationLine }}</p>
                                <h2 class="trip-card__title-on-image trip-card__title-on-image--with-sub">{{ $renterName }}</h2>
                            @else
                                <h2 class="trip-card__title-on-image">{{ $order->home?->name ?? $renterName }}</h2>
                            @endif

                            <div class="trip-card__guest">
                                <img src="{{ $renter?->avatar_path ?? \App\Models\User::getDefaultAvatar() }}"
                                     alt="{{ $renterName }}"
                                     class="trip-card__host-avatar"
                                     loading="lazy"
                                     decoding="async">
                                <span class="trip-card__host-name">{{ $renterName }}</span>
                            </div>
                        </div>

                        <div class="trip-card__body">
                            <div class="trip-card__dates">
                                <div class="trip-card__date-col">
                                    @if($checkinJ)
                                        <span>{{ persianNumber($checkinJ->format('j F')) }}</span>
                                        <span class="trip-card__date-day">{{ $checkinJ->format('l') }}</span>
                                    @else
                                        <span>—</span>
                                    @endif
                                </div>

                                <span class="trip-card__date-arrow" aria-hidden="true">&lt;</span>

                                <div class="trip-card__date-col">
                                    @if($checkoutJ)
                                        <span>{{ persianNumber($checkoutJ->format('j F')) }}</span>
                                        <span class="trip-card__date-day">{{ $checkoutJ->format('l') }}</span>
                                    @else
                                        <span>—</span>
                                    @endif
                                </div>
                            </div>

                            <p class="trip-card__meta">
                                {{ persianNumber($order->count_guest) }} نفر به مدت {{ persianNumber($nights) }} شب
                            </p>
                            <p class="trip-card__price">
                                مبلغ: {{ persianNumber($displayPrice) }} تومان
                            </p>

                            <div class="trip-card__actions">
                                <a href="{{ route('dashboard.orders.show', $order) }}"
                                   class="trip-card__btn trip-card__btn--view">
                                    مشاهده رزرو
                                </a>

                                @if($orderPresenter->canDownloadContract($order))
                                    <a href="{{ route('dashboard.orders.contract', $order) }}"
                                       class="trip-card__btn trip-card__btn--pay">
                                        <i class="bi bi-download" aria-hidden="true"></i>
                                        @lang('title.download_voucher')
                                    </a>
                                @endif
                            </div>

                            @if($orderPresenter->canHostRespondToPending($order))
                                <div class="trip-card__extra-actions">
                                    <form action="{{ route('dashboard.orders.accept', $order) }}" method="POST" class="flex-fill m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="trip-card__btn trip-card__btn--accept w-100">
                                            <i class="bi bi-check-lg" aria-hidden="true"></i>
                                            تایید
                                        </button>
                                    </form>
                                    <button type="button"
                                            class="trip-card__btn trip-card__btn--reject"
                                            onclick="rejectOrder({{ $order->id }})">
                                        رد
                                    </button>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach

                @if($orders->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->appends(['tab' => $tab, 'sort' => $sort])->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="trips-empty-icon">
                        <i class="bi bi-moon-stars"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="font-size: 16px; color: #333;">{{ $activeTab['empty_title'] }}</h5>
                    <p class="text-muted mb-0" style="font-size: 14px;">{{ $activeTab['empty_desc'] }}</p>
                </div>
            @endif
        </div>
    </div>

    @include('dashboard.partials.order-reject-modal')
@endsection

@section('scripts')
<script>
    let currentOrderId = null;

    function rejectOrder(orderId) {
        currentOrderId = orderId;
        new bootstrap.Modal(document.getElementById('rejectModal')).show();
    }

    document.getElementById('confirmReject').addEventListener('click', function () {
        if (!currentOrderId) return;

        const selected = document.querySelector('input[name="reject_reason_modal"]:checked');
        if (!selected) {
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/dashboard/orders/${currentOrderId}/reject`;

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        form.appendChild(methodInput);

        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'reject_reason';
        reasonInput.value = selected.value;
        form.appendChild(reasonInput);

        document.body.appendChild(form);
        form.submit();
    });
</script>
@endsection
