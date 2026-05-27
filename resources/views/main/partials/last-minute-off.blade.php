<link rel="stylesheet" href="{{ asset('assets/css/last-minute-off.css') }}">

<section class="last-minute-off" id="last-minute-off-section">
    <div class="last-minute-off__header">
        <div class="last-minute-off__countdown" id="last-minute-countdown" aria-label="زمان باقی‌مانده تا پایان امروز">
            <span class="last-minute-off__countdown-box" data-part="hours">۰۰</span>
            <span class="last-minute-off__countdown-sep">:</span>
            <span class="last-minute-off__countdown-box" data-part="minutes">۰۰</span>
            <span class="last-minute-off__countdown-sep">:</span>
            <span class="last-minute-off__countdown-box" data-part="seconds">۰۰</span>
        </div>
        <div class="last-minute-off__titles">
            <h2 class="last-minute-off__title">{{ setting('index:home-off-title') ?: 'تخفیف لحظه آخری' }}</h2>
            <p class="last-minute-off__subtitle">برای امروز و فردا با تخفیف رزرو کن</p>
        </div>
    </div>

    <div class="last-minute-off__tabs tab-bar-scroll" role="tablist">
        <button type="button"
                class="last-minute-off__tab active"
                data-city-id=""
                role="tab"
                aria-selected="true">
            همه شهرها
        </button>
        @foreach($offCities as $city)
            <button type="button"
                    class="last-minute-off__tab"
                    data-city-id="{{ (int) $city['id'] }}"
                    role="tab"
                    aria-selected="false">
                {{ $city['name'] }}
            </button>
        @endforeach
    </div>

    <div class="swiper last-minute-off-swiper">
        <div class="swiper-wrapper" id="last-minute-off-list"></div>
    </div>
</section>

<script>
    window.lastMinuteOffInitial = @json(($offHomesInitial ?? collect())->values());
</script>
<script src="{{ public_asset_version('assets/js/seo-image-utils.js') }}"></script>
<script src="{{ public_asset_version('assets/js/index-section-visibility.js') }}"></script>
<script src="{{ public_asset_version('assets/js/guest-rating.js') }}"></script>
<script src="{{ public_asset_version('assets/js/last-minute-off.js') }}"></script>
