@php
    $provincesForMap = \App\Models\Province::query()
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get(['id', 'name', 'latitude', 'longitude']);
@endphp

<link rel="stylesheet" href="{{ asset('assets/css/nearby-map-banner.css') }}">

<a href="{{ route('main.homes.index', ['map' => 1]) }}"
   id="nearby-map-banner"
   class="nearby-map-banner"
   data-homes-url="{{ route('main.homes.index') }}"
   aria-label="اقامتگاه‌های دوروبر — جستجو روی نقشه">
    <div class="nearby-map-banner__header">
        <h2 class="nearby-map-banner__title">اقامتگاه‌های دوروبر</h2>
        <p class="nearby-map-banner__subtitle">اقامتگاه‌های نزدیک رو در نقشه پیدا کن</p>
    </div>
    <div class="nearby-map-banner__visual">
        <img src="{{ asset('assets/images/nearby-homes-map.png') }}"
             alt="نقشه اقامتگاه‌های نزدیک — جستجو روی نقشه"
             class="nearby-map-banner__map-img"
             width="400"
             height="240"
             loading="lazy"
             decoding="async">
        <span class="nearby-map-banner__cta">
            بزن بریم
            <i class="bi bi-chevron-left" aria-hidden="true"></i>
        </span>
    </div>
</a>

<script>
    window.__provincesForNearbyMap = @json($provincesForMap);
</script>
<script src="{{ asset('assets/js/nearby-map-banner.js') }}"></script>
