@extends('layouts.main.main_mobile', ['title' => 'سفرهای من'])

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/trips-mobile.css') }}">
@endsection

@section('content')
    @include('layouts.main.partials.navbar-mobile')

    @php
        use App\Models\Order;

        $tabs = [
            Order::TRIP_TAB_CURRENT => ['label' => 'جاری', 'empty_title' => 'در حال حاضر سفر جاری ندارید', 'empty_desc' => 'شما می‌توانید سفرهای پایان‌یافته خود را مشاهده نمایید', 'empty_btn' => 'پایان‌یافته', 'empty_btn_tab' => Order::TRIP_TAB_FINISHED],
            Order::TRIP_TAB_FINISHED => ['label' => 'پایان‌یافته', 'empty_title' => 'سفر پایان‌یافته‌ای ندارید', 'empty_desc' => 'پس از اتمام اقامت و ثبت نظر، سفرها در این بخش نمایش داده می‌شوند'],
            Order::TRIP_TAB_UNSUCCESSFUL => ['label' => 'ناموفق', 'empty_title' => 'سفر ناموفقی ندارید', 'empty_desc' => 'رزروهای رد شده یا لغو شده در این بخش قرار می‌گیرند'],
            Order::TRIP_TAB_AWAITING_REVIEW => ['label' => 'در انتظار ثبت نظر', 'empty_title' => 'سفری در انتظار ثبت نظر ندارید', 'empty_desc' => 'پس از اتمام اقامت، نظر خود را ثبت کنید'],
        ];
        $activeTab = $tabs[$tab] ?? $tabs[Order::TRIP_TAB_CURRENT];
    @endphp

    <div class="trips-page">
        <div class="trips-header">
            <div class="trips-breadcrumb">
                <a href="{{ route('main.homes.index') }}">ویلافردا</a>
                <span class="mx-1">›</span>
                <span>سفرها</span>
            </div>

            <nav class="trips-tabs">
                @foreach($tabs as $tabKey => $tabInfo)
                    <a href="{{ route('dashboard.rents.index', ['tab' => $tabKey]) }}"
                       class="trips-tab {{ $tab === $tabKey ? 'active' : '' }}">
                        {{ $tabInfo['label'] }} ({{ persianNumber($counts[$tabKey] ?? 0) }})
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="trips-content">
            @if($rents->isNotEmpty())
                @foreach($rents as $rent)
                    @php
                        $checkinJ = $rent->checkin ? persianDate($rent->checkin->copy()) : null;
                        $checkoutCarbon = $rent->checkout ? $rent->checkout->copy()->addDay() : null;
                        $checkoutJ = $checkoutCarbon ? persianDate($checkoutCarbon) : null;
                        $displayPrice = $rent->discount ? $rent->payable_price : $rent->price;
                        $host = $rent->owner;
                        $hostName = $host?->first_name ?: ($host?->full_name ?? 'میزبان');
                        $orderPresenter = app(\App\Services\OrderShowPresenter::class);

                        if (in_array($tab, [Order::TRIP_TAB_AWAITING_REVIEW, Order::TRIP_TAB_FINISHED], true)) {
                            $badgeText = $rent->status === Order::DONE ? 'تکمیل شد' : 'خاتمه یافته';
                            $badgeClass = 'trip-card__badge';
                        } else {
                            $badgeText = $orderPresenter->displayStatusLabel($rent, 'renter');
                            $badgeClass = match ($orderPresenter->displayStatusColor($rent)) {
                                'danger' => 'trip-card__badge trip-card__badge--danger',
                                'warning' => 'trip-card__badge trip-card__badge--warning',
                                'success' => 'trip-card__badge',
                                default => 'trip-card__badge trip-card__badge--info',
                            };
                        }

                        $nights = max(1, (int) $rent->count_days);
                    @endphp

                    <article class="trip-card">
                        <div class="trip-card__media">
                            <img src="{{ $rent->home->cover_path }}"
                                 class="trip-card__img"
                                 alt="{{ $rent->home->name }}"
                                 loading="lazy"
                                 decoding="async">

                            <span class="{{ $badgeClass }}">
                                @if($tab === Order::TRIP_TAB_AWAITING_REVIEW || in_array($rent->status, [Order::DONE, Order::IN_RENT, Order::WAITING_FOR_RENTER]))
                                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                                @endif
                                {{ $badgeText }}
                            </span>

                            <div class="trip-card__gradient" aria-hidden="true"></div>

                            <h2 class="trip-card__title-on-image">{{ $rent->home->name }}</h2>

                            <div class="trip-card__host">
                                <img src="{{ $host?->avatar_path ?? \App\Models\User::getDefaultAvatar() }}"
                                     alt="{{ $hostName }}"
                                     class="trip-card__host-avatar"
                                     loading="lazy"
                                     decoding="async">
                                <span class="trip-card__host-name">{{ $hostName }}</span>
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
                                {{ persianNumber($rent->count_guest) }} نفر به مدت {{ persianNumber($nights) }} شب
                            </p>
                            <p class="trip-card__price">
                                مبلغ: {{ persianNumber($displayPrice) }} تومان
                            </p>

                            <div class="trip-card__actions">
                                @if($tab === Order::TRIP_TAB_AWAITING_REVIEW)
                                    <a href="{{ route('dashboard.rents.review.create', $rent) }}"
                                       class="trip-card__btn trip-card__btn--review">
                                        <i class="bi bi-chat-dots" aria-hidden="true"></i>
                                        ثبت نظر
                                    </a>
                                @endif

                                <a href="{{ route('dashboard.rents.show', $rent) }}"
                                   class="trip-card__btn trip-card__btn--view">
                                    مشاهده رزرو
                                </a>

                                @if($orderPresenter->canDownloadContract($rent))
                                    <a href="{{ route('dashboard.rents.contract', $rent) }}"
                                       class="trip-card__btn trip-card__btn--pay">
                                        <i class="bi bi-download" aria-hidden="true"></i>
                                        @lang('title.download_voucher')
                                    </a>
                                @endif
                            </div>

                            @php
                                $canPay = $orderPresenter->canRenterPay($rent);
                                $canCancel = $orderPresenter->canRenterCancel($rent);
                            @endphp
                            @if($canPay || $canCancel)
                                <div class="trip-card__extra-actions">
                                    @if($canPay)
                                        <form action="{{ route('dashboard.rents.pay', $rent) }}" method="POST" class="flex-fill m-0">
                                            @csrf
                                            <button type="submit" class="trip-card__btn trip-card__btn--pay w-100">پرداخت</button>
                                        </form>
                                    @endif

                                    @if($canCancel)
                                        <button type="button"
                                                class="trip-card__btn trip-card__btn--cancel"
                                                onclick="cancelRent({{ $rent->id }})">
                                            لغو
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach

                @if($rents->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $rents->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="trips-empty-icon">
                        <i class="bi bi-suitcase2"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="font-size: 16px; color: #333;">{{ $activeTab['empty_title'] }}</h5>
                    <p class="text-muted mb-4" style="font-size: 14px;">{{ $activeTab['empty_desc'] }}</p>
                    @if(!empty($activeTab['empty_btn_tab']))
                        <a href="{{ route('dashboard.rents.index', ['tab' => $activeTab['empty_btn_tab']]) }}"
                           class="btn btn-light px-4 py-2" style="border-radius: 20px; font-size: 14px; color: #666;">
                            {{ $activeTab['empty_btn'] }}
                        </a>
                    @elseif($tab === Order::TRIP_TAB_UNSUCCESSFUL || $tab === Order::TRIP_TAB_FINISHED)
                        <a href="{{ route('main.homes.index') }}" class="btn px-4 py-2" style="background: #D39D1A; border-color: #D39D1A; color: #fff; border-radius: 20px; font-size: 14px;">
                            جستجوی اقامتگاه
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-body text-center py-4">
                    <h5 class="fw-bold mb-2">لغو رزرو</h5>
                    <p class="text-muted mb-4" style="font-size: 14px;">آیا مطمئن هستید که می‌خواهید این رزرو را لغو کنید؟</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                        <button type="button" class="btn btn-danger" id="confirmCancel">لغو کن</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let currentRentId = null;

    function cancelRent(rentId) {
        currentRentId = rentId;
        new bootstrap.Modal(document.getElementById('cancelModal')).show();
    }

    document.getElementById('confirmCancel').addEventListener('click', function () {
        if (!currentRentId) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/dashboard/rents/${currentRentId}/cancel`;

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

        document.body.appendChild(form);
        form.submit();
    });
</script>
@endsection
