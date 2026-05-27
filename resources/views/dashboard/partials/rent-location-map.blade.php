@php
    $home = $rent->home;
    $mapLat = (float) $home->latitude;
    $mapLng = (float) $home->longitude;
    $locationLabelEncoded = rawurlencode($home->name);
    $navigationUrl = "geo:{$mapLat},{$mapLng}?q={$mapLat},{$mapLng}({$locationLabelEncoded})";
    $isPrecise = $locationMapPrecise ?? true;
@endphp

<div class="rent-location-card" id="rentLocationCard"
     data-lat="{{ $mapLat }}"
     data-lng="{{ $mapLng }}"
     data-location-mode="{{ $isPrecise ? 'precise' : 'approximate' }}">
    <h3 class="rent-location-card__title">
        <i class="bi bi-geo-alt" aria-hidden="true"></i>
        موقعیت اقامتگاه
    </h3>

    @if($home->province && $home->city)
        <p class="rent-location-card__hint">{{ $home->province->name }}، {{ $home->city->name }}</p>
    @endif

    @if($isPrecise)
        <p class="rent-location-card__hint">موقعیت دقیق اقامتگاه روی نقشه مشخص شده است.</p>
    @else
        <p class="rent-location-card__hint">محل تقریبی اقامتگاه در محدودهٔ مشخص‌شده روی نقشه نمایش داده می‌شود.</p>
    @endif

    <div id="rentLocationMap"
         class="rent-location-card__map"
         role="img"
         aria-label="{{ $isPrecise ? 'نقشه موقعیت اقامتگاه' : 'نقشه محدوده اقامتگاه' }}"></div>

    @if($isPrecise)
        <a href="{{ $navigationUrl }}"
           class="rent-location-card__nav-btn">
            <i class="bi bi-signpost-2" aria-hidden="true"></i>
            مسیریابی
        </a>
    @endif
</div>
