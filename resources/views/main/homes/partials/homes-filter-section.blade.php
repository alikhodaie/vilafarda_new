@php
    $provinces = \App\Models\Province::getFromCache();
    $selectedProvince = request('province');
    $cities = $selectedProvince ? \App\Models\City::where('province_id', $selectedProvince)->get() : collect();
    $selectedCity = request('city');
    $selectedType = request('type');
    $selectedGuestCount = request('guest_count');
    $selectedMinPrice = request('min_price');
    $selectedMaxPrice = request('max_price');
    $filterOptions = \App\Models\Option::getFromCache();
    $selectedOptions = collect(request('options', []))
        ->map(fn ($id) => (int) $id)
        ->filter()
        ->unique()
        ->values()
        ->all();

    $provinceLabel = $selectedProvince ? $provinces->firstWhere('id', $selectedProvince)?->name : null;
    $cityLabel = $selectedCity ? $cities->firstWhere('id', $selectedCity)?->name : null;
    $typeLabels = ['villa' => 'ویلا', 'apartment' => 'آپارتمان', 'house' => 'خانه'];
    $typeLabel = $selectedType ? ($typeLabels[$selectedType] ?? $selectedType) : null;
    $guestLabel = $selectedGuestCount ? ($selectedGuestCount == '10' ? '10+ نفر' : $selectedGuestCount . ' نفر') : null;
    $priceLabel = null;
    if ($selectedMinPrice || $selectedMaxPrice) {
        if ($selectedMinPrice && $selectedMaxPrice) {
            $priceLabel = persianNumber($selectedMinPrice) . ' - ' . persianNumber($selectedMaxPrice);
        } elseif ($selectedMinPrice) {
            $priceLabel = 'از ' . persianNumber($selectedMinPrice);
        } elseif ($selectedMaxPrice) {
            $priceLabel = 'تا ' . persianNumber($selectedMaxPrice);
        }
    }
    $travelDateLabel = (request('start_at') && request('end_at'))
        ? request('start_at') . ' - ' . request('end_at')
        : null;
@endphp

    <!-- Search bar + active filter chips -->
    <div class="homes-filter-search-wrap">
        @include('main.homes.partials.mobile-search-bar')
    </div>

    <!-- Filter Badges (Single Row Scrollable) -->
    <div class="homes-filter-badges-wrap">
        <div class="filter-badges-scroll" style="overflow-x: auto; overflow-y: hidden; white-space: nowrap; padding: 8px 0; -webkit-overflow-scrolling: touch; scroll-behavior: smooth;">
            <div class="filter-badges-wrapper" style="display: inline-flex; gap: 8px; align-items: center; padding: 0 4px;">
                <!-- Travel Date Filter -->
                <span class="filter-badge-btn {{ $travelDateLabel ? 'active' : '' }}"
                      id="filterDateBadgeBtn"
                      role="button"
                      tabindex="0"
                      style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: {{ $travelDateLabel ? '#D39D1A' : '#f0f0f0' }}; color: {{ $travelDateLabel ? 'white' : '#666' }}; border-radius: 20px; font-size: 13px; font-weight: 500; white-space: nowrap; cursor: pointer; user-select: none; transition: all 0.2s ease; border: 2px solid {{ $travelDateLabel ? '#D39D1A' : 'transparent' }};">
                    <i class="bi bi-calendar3" style="font-size: 14px;"></i>
                    @if(!$travelDateLabel)<span class="filter-badge-btn__label">تاریخ سفر</span>@else<span class="filter-badge-btn__label">{{ $travelDateLabel }}</span>@endif
                </span>

                <!-- Province Filter -->
                <span class="filter-badge-btn {{ $selectedProvince ? 'active' : '' }}" 
                      data-bs-toggle="modal" 
                      data-bs-target="#filterProvinceModal"
                      style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: {{ $selectedProvince ? '#D39D1A' : '#f0f0f0' }}; color: {{ $selectedProvince ? 'white' : '#666' }}; border-radius: 20px; font-size: 13px; font-weight: 500; white-space: nowrap; cursor: pointer; user-select: none; transition: all 0.2s ease; border: 2px solid {{ $selectedProvince ? '#D39D1A' : 'transparent' }};">
                    <i class="bi bi-geo-alt" style="font-size: 14px;"></i>
                    @if(!$selectedProvince)<span class="filter-badge-btn__label">استان</span>@endif
                </span>
                
                <!-- City Filter (only if province is selected) -->
                @if($selectedProvince)
                <span class="filter-badge-btn {{ $selectedCity ? 'active' : '' }}" 
                      data-bs-toggle="modal" 
                      data-bs-target="#filterCityModal"
                      style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: {{ $selectedCity ? '#D39D1A' : '#f0f0f0' }}; color: {{ $selectedCity ? 'white' : '#666' }}; border-radius: 20px; font-size: 13px; font-weight: 500; white-space: nowrap; cursor: pointer; user-select: none; transition: all 0.2s ease; border: 2px solid {{ $selectedCity ? '#D39D1A' : 'transparent' }};">
                    <i class="bi bi-geo-alt-fill" style="font-size: 14px;"></i>
                    @if(!$selectedCity)<span class="filter-badge-btn__label">شهر</span>@endif
                </span>
                @endif
                
                <!-- Type Filter -->
                <span class="filter-badge-btn {{ $selectedType ? 'active' : '' }}" 
                      data-bs-toggle="modal" 
                      data-bs-target="#filterTypeModal"
                      style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: {{ $selectedType ? '#D39D1A' : '#f0f0f0' }}; color: {{ $selectedType ? 'white' : '#666' }}; border-radius: 20px; font-size: 13px; font-weight: 500; white-space: nowrap; cursor: pointer; user-select: none; transition: all 0.2s ease; border: 2px solid {{ $selectedType ? '#D39D1A' : 'transparent' }};">
                    <i class="bi bi-house" style="font-size: 14px;"></i>
                    @if(!$selectedType)<span class="filter-badge-btn__label">نوع</span>@endif
                </span>
                
                <!-- Guest Count Filter -->
                <span class="filter-badge-btn {{ $selectedGuestCount ? 'active' : '' }}" 
                      data-bs-toggle="modal" 
                      data-bs-target="#filterGuestModal"
                      style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: {{ $selectedGuestCount ? '#D39D1A' : '#f0f0f0' }}; color: {{ $selectedGuestCount ? 'white' : '#666' }}; border-radius: 20px; font-size: 13px; font-weight: 500; white-space: nowrap; cursor: pointer; user-select: none; transition: all 0.2s ease; border: 2px solid {{ $selectedGuestCount ? '#D39D1A' : 'transparent' }};">
                    <i class="bi bi-people" style="font-size: 14px;"></i>
                    @if(!$selectedGuestCount)<span class="filter-badge-btn__label">تعداد مهمان</span>@endif
                </span>
                
                <!-- Price Filter -->
                <span class="filter-badge-btn {{ $priceLabel ? 'active' : '' }}" 
                      data-bs-toggle="modal" 
                      data-bs-target="#filterPriceModal"
                      style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: {{ $priceLabel ? '#D39D1A' : '#f0f0f0' }}; color: {{ $priceLabel ? 'white' : '#666' }}; border-radius: 20px; font-size: 13px; font-weight: 500; white-space: nowrap; cursor: pointer; user-select: none; transition: all 0.2s ease; border: 2px solid {{ $priceLabel ? '#D39D1A' : 'transparent' }};">
                    <i class="bi bi-currency-exchange" style="font-size: 14px;"></i>
                    @if(!$priceLabel)<span class="filter-badge-btn__label">قیمت</span>@endif
                </span>
                
                <!-- Features Filter -->
                <span class="filter-badge-btn {{ !empty($selectedOptions) ? 'active' : '' }}" 
                      data-bs-toggle="modal" 
                      data-bs-target="#filterFeatureModal"
                      style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: {{ !empty($selectedOptions) ? '#D39D1A' : '#f0f0f0' }}; color: {{ !empty($selectedOptions) ? 'white' : '#666' }}; border-radius: 20px; font-size: 13px; font-weight: 500; white-space: nowrap; cursor: pointer; user-select: none; transition: all 0.2s ease; border: 2px solid {{ !empty($selectedOptions) ? '#D39D1A' : 'transparent' }};">
                    <i class="bi bi-star" style="font-size: 14px;"></i>
                    @if(empty($selectedOptions))<span class="filter-badge-btn__label">امکانات</span>@endif
                </span>
            </div>
        </div>
    </div>

