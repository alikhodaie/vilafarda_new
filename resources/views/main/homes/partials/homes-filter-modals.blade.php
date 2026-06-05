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

<div class="homes-filter-modals">
    <!-- Province Filter Modal -->
    <div class="modal fade" id="filterProvinceModal" tabindex="-1" aria-labelledby="filterProvinceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterProvinceModalLabel" style="font-size: 16px;">انتخاب استان</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ route('main.homes.index') }}" id="provinceFilterForm" data-current-province="{{ request('province') }}">
                    <input type="hidden" name="city" value="{{ request('city') }}">
                    <input type="hidden" name="type" value="{{ request('type') }}">
                    <input type="hidden" name="guest_count" value="{{ request('guest_count') }}">
                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                    @include('main.homes.partials.homes-filter-options-hidden')
                    @include('main.homes.partials.homes-filter-query-hidden')
                    <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                        <div class="list-group">
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action {{ !$selectedProvince ? 'active' : '' }}" 
                               onclick="selectProvince('')" style="border-radius: 8px; margin-bottom: 8px; cursor: pointer;">
                                <i class="bi bi-geo-alt me-2"></i>همه استان‌ها
                            </a>
                            @foreach($provinces as $province)
                                <a href="javascript:void(0)" class="list-group-item list-group-item-action {{ $selectedProvince == $province->id ? 'active' : '' }}" 
                                   onclick="selectProvince('{{ $province->id }}')" style="border-radius: 8px; margin-bottom: 8px; cursor: pointer;">
                                    <i class="bi bi-geo-alt me-2"></i>{{ $province->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 14px; border-radius: 12px;">انصراف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- City Filter Modal -->
    @if($selectedProvince)
    <div class="modal fade" id="filterCityModal" tabindex="-1" aria-labelledby="filterCityModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterCityModalLabel" style="font-size: 16px;">انتخاب شهر</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ route('main.homes.index') }}" id="cityFilterForm">
                    <input type="hidden" name="province" value="{{ request('province') }}">
                    <input type="hidden" name="type" value="{{ request('type') }}">
                    <input type="hidden" name="guest_count" value="{{ request('guest_count') }}">
                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                    @include('main.homes.partials.homes-filter-options-hidden')
                    @include('main.homes.partials.homes-filter-query-hidden')
                    <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                        <div class="list-group">
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action {{ !$selectedCity ? 'active' : '' }}" 
                               onclick="selectCity('')" style="border-radius: 8px; margin-bottom: 8px; cursor: pointer;">
                                <i class="bi bi-geo-alt-fill me-2"></i>همه شهرها
                            </a>
                            @foreach($cities as $city)
                                <a href="javascript:void(0)" class="list-group-item list-group-item-action {{ $selectedCity == $city->id ? 'active' : '' }}" 
                                   onclick="selectCity('{{ $city->id }}')" style="border-radius: 8px; margin-bottom: 8px; cursor: pointer;">
                                    <i class="bi bi-geo-alt-fill me-2"></i>{{ $city->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 14px; border-radius: 12px;">انصراف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Type Filter Modal -->
    <div class="modal fade" id="filterTypeModal" tabindex="-1" aria-labelledby="filterTypeModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterTypeModalLabel" style="font-size: 16px;">انتخاب نوع اقامتگاه</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ route('main.homes.index') }}" id="typeFilterForm">
                    <input type="hidden" name="province" value="{{ request('province') }}">
                    <input type="hidden" name="city" value="{{ request('city') }}">
                    <input type="hidden" name="guest_count" value="{{ request('guest_count') }}">
                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                    @include('main.homes.partials.homes-filter-options-hidden')
                    @include('main.homes.partials.homes-filter-query-hidden')
                    <div class="modal-body">
                        <div class="list-group">
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action {{ !$selectedType ? 'active' : '' }}" 
                               onclick="selectType('')" style="border-radius: 8px; margin-bottom: 8px; cursor: pointer;">
                                <i class="bi bi-house me-2"></i>همه انواع
                            </a>
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action {{ $selectedType == 'villa' ? 'active' : '' }}" 
                               onclick="selectType('villa')" style="border-radius: 8px; margin-bottom: 8px; cursor: pointer;">
                                <i class="bi bi-house me-2"></i>ویلا
                            </a>
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action {{ $selectedType == 'apartment' ? 'active' : '' }}" 
                               onclick="selectType('apartment')" style="border-radius: 8px; margin-bottom: 8px; cursor: pointer;">
                                <i class="bi bi-building me-2"></i>آپارتمان
                            </a>
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action {{ $selectedType == 'house' ? 'active' : '' }}" 
                               onclick="selectType('house')" style="border-radius: 8px; margin-bottom: 8px; cursor: pointer;">
                                <i class="bi bi-house-door me-2"></i>خانه
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 14px; border-radius: 12px;">انصراف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Guest Count Filter Modal -->
    <div class="modal fade" id="filterGuestModal" tabindex="-1" aria-labelledby="filterGuestModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterGuestModalLabel" style="font-size: 16px;">انتخاب تعداد مهمان</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ route('main.homes.index') }}" id="guestFilterForm">
                    <input type="hidden" name="province" value="{{ request('province') }}">
                    <input type="hidden" name="city" value="{{ request('city') }}">
                    <input type="hidden" name="type" value="{{ request('type') }}">
                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                    @include('main.homes.partials.homes-filter-options-hidden')
                    @include('main.homes.partials.homes-filter-query-hidden')
                    <div class="modal-body">
                        <div class="list-group">
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action {{ !$selectedGuestCount ? 'active' : '' }}" 
                               onclick="selectGuestCount('')" style="border-radius: 8px; margin-bottom: 8px; cursor: pointer;">
                                <i class="bi bi-people me-2"></i>همه
                            </a>
                            @foreach([1, 2, 4, 6, 8, 10] as $count)
                                <a href="javascript:void(0)" class="list-group-item list-group-item-action {{ $selectedGuestCount == $count ? 'active' : '' }}" 
                                   onclick="selectGuestCount('{{ $count }}')" style="border-radius: 8px; margin-bottom: 8px; cursor: pointer;">
                                    <i class="bi bi-people me-2"></i>{{ $count == 10 ? '10+' : $count }} نفر
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 14px; border-radius: 12px;">انصراف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Price Filter Modal -->
    <div class="modal fade" id="filterPriceModal" tabindex="-1" aria-labelledby="filterPriceModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterPriceModalLabel" style="font-size: 16px;">انتخاب محدوده قیمت</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ route('main.homes.index') }}" id="priceFilterForm">
                    <input type="hidden" name="province" value="{{ request('province') }}">
                    <input type="hidden" name="city" value="{{ request('city') }}">
                    <input type="hidden" name="type" value="{{ request('type') }}">
                    <input type="hidden" name="guest_count" value="{{ request('guest_count') }}">
                    @include('main.homes.partials.homes-filter-options-hidden')
                    @include('main.homes.partials.homes-filter-query-hidden')
                    <div class="modal-body pt-2 overflow-hidden">
                        <div class="mobile-price-range-col">
                            @include('main.homes.partials.mobile-price-range', ['mprId' => 'mprModal'])
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 14px; border-radius: 12px;">انصراف</button>
                        <button type="submit" class="btn btn-primary" style="background: #D39D1A; border-color: #D39D1A; color: white; font-size: 14px; border-radius: 12px;">
                            <i class="bi bi-check-circle me-2"></i>
                            اعمال
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Features Filter Modal -->
    <div class="modal fade" id="filterFeatureModal" tabindex="-1" aria-labelledby="filterFeatureModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterFeatureModalLabel" style="font-size: 16px;">انتخاب امکانات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ route('main.homes.index') }}" id="featureFilterForm">
                    <input type="hidden" name="province" value="{{ request('province') }}">
                    <input type="hidden" name="city" value="{{ request('city') }}">
                    <input type="hidden" name="type" value="{{ request('type') }}">
                    <input type="hidden" name="guest_count" value="{{ request('guest_count') }}">
                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                    @include('main.homes.partials.homes-filter-query-hidden')
                    <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                        @include('main.homes.partials.homes-filter-options-grid', ['optionsIdPrefix' => 'featureModal'])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 14px; border-radius: 12px;">انصراف</button>
                        <button type="submit" class="btn btn-primary" style="background: #D39D1A; border-color: #D39D1A; color: white; font-size: 14px; border-radius: 12px;">
                            <i class="bi bi-check-circle me-2"></i>
                            اعمال
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade modal-above-map-explorer" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel" style="font-size: 16px;">فیلتر اقامتگاه‌ها</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ route('main.homes.index') }}">
                    @foreach(request('q', []) as $term)
                        @if(is_string($term) && trim($term) !== '')
                            <input type="hidden" name="q[]" value="{{ $term }}">
                        @endif
                    @endforeach
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Province -->
                            <div class="col-12">
                                <label for="province" class="form-label" style="font-size: 14px;">استان</label>
                                <select name="province" id="province" class="form-select" style="font-size: 14px;">
                                    <option value="">انتخاب استان</option>
                                    @foreach(\App\Models\Province::getFromCache() as $province)
                                        <option value="{{ $province->id }}" 
                                                @if(request('province') == $province->id) selected @endif>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- City -->
                            <div class="col-12">
                                <label for="city" class="form-label" style="font-size: 14px;">شهر</label>
                                <select name="city" id="city" class="form-select" style="font-size: 14px;">
                                    <option value="">انتخاب شهر</option>
                                    @if(request('province'))
                                        @foreach(\App\Models\City::where('province_id', request('province'))->get() as $city)
                                            <option value="{{ $city->id }}" 
                                                    @if(request('city') == $city->id) selected @endif>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- Type -->
                            <div class="col-6">
                                <label for="type" class="form-label" style="font-size: 14px;">نوع اقامتگاه</label>
                                <select name="type" id="type" class="form-select" style="font-size: 14px;">
                                    <option value="">همه انواع</option>
                                    <option value="villa" @if(request('type') == 'villa') selected @endif>ویلا</option>
                                    <option value="apartment" @if(request('type') == 'apartment') selected @endif>آپارتمان</option>
                                    <option value="house" @if(request('type') == 'house') selected @endif>خانه</option>
                                </select>
                            </div>

                            <!-- Guest Count -->
                            <div class="col-6">
                                <label for="guest_count" class="form-label" style="font-size: 14px;">تعداد مهمان</label>
                                <select name="guest_count" id="guest_count" class="form-select" style="font-size: 14px;">
                                    <option value="">همه</option>
                                    <option value="1" @if(request('guest_count') == '1') selected @endif>1 نفر</option>
                                    <option value="2" @if(request('guest_count') == '2') selected @endif>2 نفر</option>
                                    <option value="4" @if(request('guest_count') == '4') selected @endif>4 نفر</option>
                                    <option value="6" @if(request('guest_count') == '6') selected @endif>6 نفر</option>
                                    <option value="8" @if(request('guest_count') == '8') selected @endif>8 نفر</option>
                                    <option value="10" @if(request('guest_count') == '10') selected @endif>10+ نفر</option>
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div class="col-12 mobile-price-range-col">
                                @include('main.homes.partials.mobile-price-range', ['mprId' => 'mprFilter'])
                            </div>

                            <!-- Features -->
                            <div class="col-12">
                                <label class="form-label" style="font-size: 14px;">امکانات</label>
                                @include('main.homes.partials.homes-filter-options-grid', ['optionsIdPrefix' => 'allFiltersModal'])
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 14px; border-radius: 12px;">انصراف</button>
                        <button type="submit" class="btn btn-primary" style="background: #D39D1A; border-color: #D39D1A; color: white; font-size: 14px; border-radius: 12px;">
                            <i class="bi bi-funnel me-2"></i>
                            اعمال فیلتر
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
