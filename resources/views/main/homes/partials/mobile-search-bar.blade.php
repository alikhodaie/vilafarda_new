@php
    $searchTerms = request('q', []);
    if (! is_array($searchTerms)) {
        $searchTerms = $searchTerms ? [(string) $searchTerms] : [];
    }
    $searchTerms = array_values(array_unique(array_filter(array_map(
        fn ($t) => trim((string) $t),
        $searchTerms
    ), fn ($t) => $t !== '')));

    $searchChips = [];

    foreach ($searchTerms as $term) {
        $searchChips[] = ['key' => 'q', 'value' => $term, 'label' => $term];
    }
    if (!empty($provinceLabel)) {
        $searchChips[] = ['key' => 'province', 'label' => $provinceLabel];
    }
    if (!empty($cityLabel)) {
        $searchChips[] = ['key' => 'city', 'label' => $cityLabel];
    }
    if (!empty($typeLabel)) {
        $searchChips[] = ['key' => 'type', 'label' => $typeLabel];
    }
    if (!empty($guestLabel)) {
        $searchChips[] = ['key' => 'guest_count', 'label' => $guestLabel];
    }
    if (!empty($priceLabel)) {
        $searchChips[] = ['key' => 'price', 'label' => $priceLabel];
    }
    if (request('start_at') && request('end_at')) {
        $searchChips[] = ['key' => 'travel_dates', 'label' => request('start_at') . ' – ' . request('end_at')];
    }
    foreach ($selectedFeatures as $featureKey) {
        $searchChips[] = [
            'key' => 'feature',
            'value' => $featureKey,
            'label' => $featureLabels[$featureKey] ?? $featureKey,
        ];
    }

    $hasSearchChips = count($searchChips) > 0;
@endphp

<div class="homes-mobile-search-wrap">
    <form class="homes-mobile-search-form" action="{{ route('main.homes.index') }}" method="get" id="homesMobileSearchForm">
        @if(request('province'))
            <input type="hidden" name="province" value="{{ request('province') }}">
        @endif
        @if(request('city'))
            <input type="hidden" name="city" value="{{ request('city') }}">
        @endif
        @if(request('type'))
            <input type="hidden" name="type" value="{{ request('type') }}">
        @endif
        @if(request('guest_count'))
            <input type="hidden" name="guest_count" value="{{ request('guest_count') }}">
        @endif
        @if(request('min_price'))
            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
        @endif
        @if(request('max_price'))
            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
        @endif
        @if(request('start_at'))
            <input type="hidden" name="start_at" value="{{ request('start_at') }}">
        @endif
        @if(request('end_at'))
            <input type="hidden" name="end_at" value="{{ request('end_at') }}">
        @endif
        @if(request('filter'))
            <input type="hidden" name="filter" value="{{ request('filter') }}">
        @endif
        @if(request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
        @endif
        @foreach($selectedFeatures as $feature)
            <input type="hidden" name="features[]" value="{{ $feature }}">
        @endforeach
        @foreach($searchTerms as $term)
            <input type="hidden" name="q[]" value="{{ $term }}" data-search-term="1">
        @endforeach

        <div class="homes-mobile-search-input-wrap {{ $hasSearchChips ? 'has-chips' : '' }}">
            @if($hasSearchChips)
                <div class="homes-mobile-search-chips" id="homesSearchChips" aria-label="فیلترهای فعال">
                    @foreach($searchChips as $chip)
                        <span class="homes-search-chip">
                            <span class="homes-search-chip__label">{{ $chip['label'] }}</span>
                            <button
                                type="button"
                                class="homes-search-chip__remove"
                                aria-label="حذف {{ $chip['label'] }}"
                                onclick="clearFilterChip({{ json_encode($chip['key']) }}@if(!empty($chip['value'])), {{ json_encode($chip['value']) }}@endif)"
                            >&times;</button>
                        </span>
                    @endforeach
                </div>
            @endif
            <input
                type="text"
                id="homesMobileSearchInput"
                class="homes-mobile-search-input"
                value=""
                placeholder="{{ $hasSearchChips ? 'افزودن کلمه جستجو...' : __('text.homes_search_placeholder') }}"
                aria-label="افزودن کلمه جستجو"
                autocomplete="off"
            >
        </div>
        <button type="submit" class="homes-mobile-search-btn" aria-label="جستجو">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>
