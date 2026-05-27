@php
    $mprId = $mprId ?? 'mpr';
    $boundsMin = (int) ($priceBoundsMin ?? 0);
    $boundsMax = (int) ($priceBoundsMax ?? 0);
    if ($boundsMax <= $boundsMin) {
        $boundsMax = $boundsMin + 1_000_000;
    }
    $valueMin = request()->filled('min_price') ? (int) request('min_price') : $boundsMin;
    $valueMax = request()->filled('max_price') ? (int) request('max_price') : $boundsMax;
    $valueMin = max($boundsMin, min($valueMin, $boundsMax));
    $valueMax = max($valueMin, min($valueMax, $boundsMax));
    $step = max(10000, (int) round(($boundsMax - $boundsMin) / 120));
    $minInputName = $minInputName ?? 'min_price';
    $maxInputName = $maxInputName ?? 'max_price';
@endphp

<div
    class="mobile-price-range"
    data-mobile-price-range
    id="{{ $mprId }}"
    data-bounds-min="{{ $boundsMin }}"
    data-bounds-max="{{ $boundsMax }}"
    data-value-min="{{ $valueMin }}"
    data-value-max="{{ $valueMax }}"
    data-step="{{ $step }}"
>
    <div class="mobile-price-range__head">
        <span class="mobile-price-range__accent" aria-hidden="true"></span>
        <h6 class="mobile-price-range__title">محدوده اجاره‌بها</h6>
    </div>

    <div class="mobile-price-range__slider-wrap">
        <div class="mobile-price-range__track" aria-hidden="true"></div>
        <div class="mobile-price-range__fill" data-mpr-fill></div>
        <input type="range" class="mobile-price-range__input mobile-price-range__input--min" data-mpr-min-range aria-label="حداقل قیمت">
        <input type="range" class="mobile-price-range__input mobile-price-range__input--max" data-mpr-max-range aria-label="حداکثر قیمت">
    </div>

    <div class="mobile-price-range__fields">
        <div class="mobile-price-range__field mobile-price-range__field--min">
            <label for="{{ $mprId }}_min_display">نرخ هر شب از</label>
            <div class="mobile-price-range__box">
                <input
                    type="text"
                    id="{{ $mprId }}_min_display"
                    class="mobile-price-range__display"
                    data-mpr-min-display
                    inputmode="numeric"
                    autocomplete="off"
                    value="{{ number_format($valueMin) }}"
                >
                <span class="mobile-price-range__currency">تومان</span>
            </div>
            <input type="hidden" name="{{ $minInputName }}" data-mpr-min-hidden value="{{ request('min_price') }}">
        </div>
        <div class="mobile-price-range__field mobile-price-range__field--max">
            <label for="{{ $mprId }}_max_display">تا</label>
            <div class="mobile-price-range__box">
                <input
                    type="text"
                    id="{{ $mprId }}_max_display"
                    class="mobile-price-range__display"
                    data-mpr-max-display
                    inputmode="numeric"
                    autocomplete="off"
                    value="{{ number_format($valueMax) }}"
                >
                <span class="mobile-price-range__currency">تومان</span>
            </div>
            <input type="hidden" name="{{ $maxInputName }}" data-mpr-max-hidden value="{{ request('max_price') }}">
        </div>
    </div>
</div>
