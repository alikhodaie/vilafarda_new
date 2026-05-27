@php use App\Models\Home; @endphp
<form class="filter-form" action="{{ route('main.homes.index') }}">
    <input type="hidden" name="filter" value="{{ request('filter') }}">

    <!-- Mobile Filter Toggle -->
    <div class="mobile-filter-toggle d-md-none">
        <button type="button" class="filter-toggle-btn" data-toggle="collapse" data-target="#filtermap" aria-expanded="false">
            <i class="fas fa-filter"></i>
            <span>فیلتر</span>
            <i class="fas fa-chevron-down toggle-icon"></i>
        </button>
    </div>

    <!-- Desktop Filter (hidden on mobile) -->
    <div class="desktop-filter d-none d-md-block">
        <div class="filter-row">
            <div class="search-section">
                <div class="input-group">
                    <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                           placeholder="@lang('title.search')">
                    <div class="input-group-append">
                        <button type="submit" class="input-group-text"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="filter-section">
                <a class="map_filter" data-toggle="collapse" href="#filtermap" role="button" aria-expanded="false"
                   aria-controls="filtermap"><i class="fa fa-sliders-h ml-2"></i>@lang('title.filter')</a>
            </div>
        </div>
    </div>
    <!-- Filter Content -->
    <div class="filter-content">
        <div class="collapse" id="filtermap">
            <div class="filter-panel">

                <!-- Sort Section -->
                <div class="filter-section">
                    <div class="form-group">
                        <select name="sort" class="modern-select">
                            <option value="">@lang('title.sort_by')</option>
                            <option value="latest" @if(request('sort') === 'latest') selected @endif>@lang('title.latest')</option>
                            <option value="cheap" @if(request('sort') === 'cheap') selected @endif>@lang('title.cheap')</option>
                            <option value="expensive" @if(request('sort') === 'expensive') selected @endif>@lang('title.expensive')</option>
                            <option value="popular" @if(request('sort') === 'popular') selected @endif>@lang('title.popular')</option>
                        </select>
                    </div>
                </div>

                <!-- Location Section -->
                <div class="filter-section">
                    <h6 class="filter-title">مکان</h6>
                    <div class="location-inputs">
                        <div class="form-group">
                            <province-input
                                route="{{ route('main.provinces') }}"
                                placeholder="@lang('text.insert_province')"
                                select_label="@lang('title.select')"
                                selected_label="@lang('title.selected')"
                                deselect_label="@lang('title.remove')"
                                no_result_text="@lang('text.empty_result')"
                                no_options_text="@lang('text.empty_list')"
                                old="{{ request('province') }}"
                            ></province-input>
                        </div>
                        <div class="form-group">
                            <city-input
                                placeholder="@lang('text.insert_city')"
                                select_label="@lang('title.select')"
                                selected_label="@lang('title.selected')"
                                deselect_label="@lang('title.remove')"
                                no_result_text="@lang('text.empty_result')"
                                no_options_text="@lang('text.empty_list')"
                                old="{{ request('city') }}"
                            ></city-input>
                        </div>
                    </div>
                </div>

                <!-- Price Range -->
                @if($min && $max)
                    <div class="filter-section">
                        <h6 class="filter-title">@lang('title.price')</h6>
                        <div class="price-input-container">
                            <div class="price-inputs">
                                <div class="price-input-group">
                                    <label class="price-label">حداقل قیمت</label>
                                    <div class="price-input-wrapper">
                                        <input type="number" 
                                               class="price-input" 
                                               name="min_price" 
                                               placeholder="{{ number_format($min) }}"
                                               min="{{ $min }}" 
                                               max="{{ $max }}"
                                               value="{{ request('min_price') }}"
                                               onchange="updatePriceRange()">
                                        <span class="price-currency">تومان</span>
                                    </div>
                                </div>
                                <div class="price-separator">تا</div>
                                <div class="price-input-group">
                                    <label class="price-label">حداکثر قیمت</label>
                                    <div class="price-input-wrapper">
                                        <input type="number" 
                                               class="price-input" 
                                               name="max_price" 
                                               placeholder="{{ number_format($max) }}"
                                               min="{{ $min }}" 
                                               max="{{ $max }}"
                                               value="{{ request('max_price') }}"
                                               onchange="updatePriceRange()">
                                        <span class="price-currency">تومان</span>
                                    </div>
                                </div>
                            </div>
                            <div class="price-range-display">
                                <span class="price-range-text">
                                    @if(request('min_price') || request('max_price'))
                                        @if(request('min_price'))
                                            از {{ number_format(request('min_price')) }} تومان
                                        @endif
                                        @if(request('min_price') && request('max_price'))
                                            تا
                                        @endif
                                        @if(request('max_price'))
                                            {{ number_format(request('max_price')) }} تومان
                                        @endif
                                    @else
                                        محدوده قیمت: {{ number_format($min) }} - {{ number_format($max) }} تومان
                                    @endif
                                </span>
                            </div>
                            <div class="price-presets">
                                <button type="button" class="price-preset" data-min="{{ $min }}" data-max="{{ round($min + ($max - $min) * 0.3) }}">
                                    ارزان
                                </button>
                                <button type="button" class="price-preset" data-min="{{ round($min + ($max - $min) * 0.3) }}" data-max="{{ round($min + ($max - $min) * 0.7) }}">
                                    متوسط
                                </button>
                                <button type="button" class="price-preset" data-min="{{ round($min + ($max - $min) * 0.7) }}" data-max="{{ $max }}">
                                    گران
                                </button>
                                <button type="button" class="price-preset" data-min="" data-max="">
                                    همه
                                </button>
                            </div>
                            <!-- Hidden field for price_range in the expected format -->
                            <input type="hidden" name="price_range" id="price_range_hidden" value="{{ request('price_range') ?: (request('min_price') && request('max_price') ? request('min_price') . ';' . request('max_price') : '') }}">
                        </div>
                    </div>
                @endif

                <!-- Atmosphere Section -->
                <div class="filter-section">
                    <h6 class="filter-title">@lang('title.atmosphere')</h6>
                    <div class="filter-options">
                        @foreach(\App\Models\Home::ATMOSPHERES as $atmosphere)
                            <label class="filter-option">
                                <input @if(request('atmospheres') && in_array($atmosphere['value'], request('atmospheres'))) checked
                                       @endif id="{{ $atmosphere['value'] }}" value="{{ $atmosphere['value'] }}"
                                       class="filter-checkbox" name="atmospheres[]" type="checkbox">
                                <span class="filter-label">{{ $atmosphere['fa_text'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Type Section -->
                <div class="filter-section">
                    <h6 class="filter-title">@lang('title.type')</h6>
                    <div class="filter-options">
                        @foreach(Home::TYPES as $type)
                            <label class="filter-option">
                                <input @if(request('types') && in_array($type['value'], request('types'))) checked
                                       @endif id="{{ $type['value'] }}" value="{{ $type['value'] }}"
                                       class="filter-checkbox" name="types[]" type="checkbox">
                                <span class="filter-label">{{ $type['fa_text'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Area Section -->
                <div class="filter-section">
                    <h6 class="filter-title">@lang('title.area')</h6>
                    <div class="filter-options">
                        @foreach(\App\Models\Home::AREAS as $area)
                            <label class="filter-option">
                                <input @if(request('areas') && in_array($area['value'], request('areas'))) checked
                                       @endif id="{{ $area['value'] }}" value="{{ $area['value'] }}"
                                       class="filter-checkbox" name="areas[]" type="checkbox">
                                <span class="filter-label">{{ $area['fa_text'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Capacity Section -->
                <div class="filter-section">
                    <h6 class="filter-title">ظرفیت</h6>
                    <div class="capacity-inputs">
                        <div class="form-group">
                            <label for="bed_count" class="input-label">@lang('title.bed_count')</label>
                            <input type="number" min="1" class="modern-input" id="bed_count" name="bed_count"
                                   value="{{ request('bed_count') }}" placeholder="تعداد تخت">
                        </div>
                        <div class="form-group">
                            <label for="bedroom_count" class="input-label">@lang('title.bedroom_count')</label>
                            <input type="number" min="1" class="modern-input" id="bedroom_count" name="bedroom_count"
                                   value="{{ request('bedroom_count') }}" placeholder="تعداد اتاق خواب">
                        </div>
                    </div>
                </div>

                <!-- Date and Guest Section -->
                <div class="filter-section">
                    @include('main.homes.partials.index-filter')
                </div>

                <!-- Action Buttons -->
                <div class="filter-actions">
                    <button type="submit" class="search-btn">@lang('title.search')</button>
                    <button type="button" class="clear-btn" onclick="clearFilters()">پاک کردن</button>
                </div>

            </div>
        </div>
    </div>
</form>

<!-- Modern Filter Styles -->
<style>
    /* Mobile Filter Toggle */
    .mobile-filter-toggle {
        margin: 16px;
    }

    .filter-toggle-btn {
        width: 100%;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 15px;
        color: #333;
        transition: all 0.2s ease;
    }

    .filter-toggle-btn:hover {
        background: #e9ecef;
        border-color: #007bff;
    }

    .filter-toggle-btn i {
        font-size: 16px;
    }

    .toggle-icon {
        transition: transform 0.2s ease;
    }

    .filter-toggle-btn[aria-expanded="true"] .toggle-icon {
        transform: rotate(180deg);
    }

    /* Filter Panel */
    .filter-panel {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px;
        margin: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .filter-section {
        margin-bottom: 24px;
    }

    .filter-section:last-child {
        margin-bottom: 0;
    }

    .filter-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 12px;
        display: block;
    }

    /* Modern Select */
    .modern-select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        background: #f8f9fa;
        font-size: 15px;
        color: #333;
        transition: all 0.2s ease;
    }

    .modern-select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        background: #ffffff;
    }

    /* Location Inputs */
    .location-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    @media (max-width: 768px) {
        .location-inputs {
            grid-template-columns: 1fr;
        }
    }

    /* Filter Options */
    .filter-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 8px;
    }

    .filter-option {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .filter-option:hover {
        background: #e9ecef;
        border-color: #007bff;
    }

    .filter-checkbox {
        margin-left: 8px;
        width: 16px;
        height: 16px;
        accent-color: #007bff;
    }

    .filter-label {
        color: #333;
        font-weight: 500;
    }

    .filter-option:has(.filter-checkbox:checked) {
        background: #e3f2fd;
        border-color: #007bff;
    }

    .filter-option:has(.filter-checkbox:checked) .filter-label {
        color: #007bff;
        font-weight: 600;
    }

    /* Capacity Inputs */
    .capacity-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    @media (max-width: 768px) {
        .capacity-inputs {
            grid-template-columns: 1fr;
        }
    }

    .input-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #555;
        margin-bottom: 6px;
    }

    .modern-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        background: #f8f9fa;
        font-size: 15px;
        color: #333;
        transition: all 0.2s ease;
    }

    .modern-input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        background: #ffffff;
    }

    .modern-input::placeholder {
        color: #6c757d;
        font-size: 14px;
    }

    /* Price Input Container */
    .price-input-container {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e9ecef;
    }

    .price-inputs {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 16px;
        align-items: end;
        margin-bottom: 16px;
    }

    @media (max-width: 768px) {
        .price-inputs {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    }

    .price-input-group {
        display: flex;
        flex-direction: column;
    }

    .price-label {
        font-size: 14px;
        font-weight: 500;
        color: #555;
        margin-bottom: 8px;
        text-align: center;
    }

    .price-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .price-input-wrapper:focus-within {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    .price-input {
        flex: 1;
        border: none;
        padding: 12px 16px;
        font-size: 15px;
        color: #333;
        background: transparent;
        outline: none;
        text-align: center;
        font-family: inherit;
    }

    .price-input::placeholder {
        color: #6c757d;
        font-size: 14px;
    }

    .price-currency {
        background: #f8f9fa;
        color: #6c757d;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 500;
        border-left: 1px solid #e9ecef;
        white-space: nowrap;
    }

    .price-separator {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 8px;
    }

    @media (max-width: 768px) {
        .price-separator {
            margin-bottom: 0;
        }
    }

    .price-range-display {
        text-align: center;
        margin-bottom: 16px;
        padding: 12px;
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .price-range-text {
        font-size: 14px;
        color: #333;
        font-weight: 500;
    }

    .price-presets {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        gap: 8px;
    }

    .price-preset {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 14px;
        font-weight: 500;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .price-preset:hover {
        background: #e9ecef;
        border-color: #007bff;
        color: #007bff;
    }

    .price-preset.active {
        background: #007bff;
        border-color: #007bff;
        color: #ffffff;
    }

    /* Filter Actions */
    .filter-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }

    .search-btn {
        flex: 1;
        background: #007bff;
        color: white;
        border: none;
        padding: 14px 20px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .search-btn:hover {
        background: #0056b3;
    }

    .clear-btn {
        background: #f8f9fa;
        color: #6c757d;
        border: 1px solid #e9ecef;
        padding: 14px 20px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .clear-btn:hover {
        background: #e9ecef;
        color: #495057;
    }

    /* Desktop Filter Styles */
    .desktop-filter {
        margin: 20px 0;
    }

    .filter-row {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .search-section {
        flex: 1;
    }

    .filter-section {
        flex-shrink: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .filter-panel {
            margin: 8px;
            padding: 16px;
            border-radius: 12px;
        }

        .filter-options {
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        }
    }
</style>

    <script>
        function updatePriceRange() {
            const minPriceInput = document.querySelector('input[name="min_price"]');
            const maxPriceInput = document.querySelector('input[name="max_price"]');
            const priceRangeHidden = document.getElementById('price_range_hidden');
            
            if (minPriceInput && maxPriceInput && priceRangeHidden) {
                const minValue = minPriceInput.value;
                const maxValue = maxPriceInput.value;
                
                if (minValue && maxValue) {
                    priceRangeHidden.value = minValue + ';' + maxValue;
                } else if (minValue) {
                    priceRangeHidden.value = minValue + ';' + minValue;
                } else if (maxValue) {
                    priceRangeHidden.value = maxValue + ';' + maxValue;
                } else {
                    priceRangeHidden.value = '';
                }
            }
        }

        function clearFilters() {
            // Clear all form inputs
            const form = document.querySelector('.filter-form');
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });
            
            // Clear price presets
            const pricePresets = document.querySelectorAll('.price-preset');
            pricePresets.forEach(preset => preset.classList.remove('active'));
            
            // Clear price range hidden field
            const priceRangeHidden = document.getElementById('price_range_hidden');
            if (priceRangeHidden) {
                priceRangeHidden.value = '';
            }
            
            // Submit the form to reset results
            form.submit();
        }

        // Toggle icon animation
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.querySelector('.filter-toggle-btn');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const icon = this.querySelector('.toggle-icon');
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !isExpanded);
                });
            }

            // Price preset functionality
            const pricePresets = document.querySelectorAll('.price-preset');
            const minPriceInput = document.querySelector('input[name="min_price"]');
            const maxPriceInput = document.querySelector('input[name="max_price"]');

            pricePresets.forEach(preset => {
                preset.addEventListener('click', function() {
                    // Remove active class from all presets
                    pricePresets.forEach(p => p.classList.remove('active'));
                    
                    // Add active class to clicked preset
                    this.classList.add('active');
                    
                    // Get min and max values
                    const minValue = this.getAttribute('data-min');
                    const maxValue = this.getAttribute('data-max');
                    
                    // Set input values
                    if (minValue) {
                        minPriceInput.value = minValue;
                    } else {
                        minPriceInput.value = '';
                    }
                    
                    if (maxValue) {
                        maxPriceInput.value = maxValue;
                    } else {
                        maxPriceInput.value = '';
                    }
                    
                    // Update display
                    updatePriceDisplay();
                    updatePriceRange();
                });
            });

            // Price input change handlers
            if (minPriceInput && maxPriceInput) {
                minPriceInput.addEventListener('input', function() {
                    updatePriceDisplay();
                    checkActivePreset();
                    updatePriceRange();
                });
                
                maxPriceInput.addEventListener('input', function() {
                    updatePriceDisplay();
                    checkActivePreset();
                    updatePriceRange();
                });
            }

            function updatePriceDisplay() {
                const minValue = minPriceInput.value;
                const maxValue = maxPriceInput.value;
                const priceRangeText = document.querySelector('.price-range-text');
                
                if (priceRangeText) {
                    if (minValue || maxValue) {
                        let text = '';
                        if (minValue) {
                            text += 'از ' + Number(minValue).toLocaleString() + ' تومان';
                        }
                        if (minValue && maxValue) {
                            text += ' تا ';
                        }
                        if (maxValue) {
                            text += Number(maxValue).toLocaleString() + ' تومان';
                        }
                        priceRangeText.textContent = text;
                    } else {
                        const min = minPriceInput.getAttribute('min');
                        const max = maxPriceInput.getAttribute('max');
                        priceRangeText.textContent = `محدوده قیمت: ${Number(min).toLocaleString()} - ${Number(max).toLocaleString()} تومان`;
                    }
                }
            }

            function checkActivePreset() {
                const minValue = minPriceInput.value;
                const maxValue = maxPriceInput.value;
                
                // Remove active class from all presets
                pricePresets.forEach(preset => preset.classList.remove('active'));
                
                // Check if current values match any preset
                pricePresets.forEach(preset => {
                    const presetMin = preset.getAttribute('data-min');
                    const presetMax = preset.getAttribute('data-max');
                    
                    if ((!minValue && !presetMin) && (!maxValue && !presetMax)) {
                        preset.classList.add('active');
                    } else if (minValue === presetMin && maxValue === presetMax) {
                        preset.classList.add('active');
                    }
                });
            }

            function initializeFromPriceRange() {
                const priceRangeHidden = document.getElementById('price_range_hidden');
                if (priceRangeHidden && priceRangeHidden.value) {
                    const range = priceRangeHidden.value.split(';');
                    if (range.length === 2) {
                        minPriceInput.value = range[0];
                        maxPriceInput.value = range[1];
                    }
                }
            }

            // Initialize from existing price_range parameter
            initializeFromPriceRange();

            // Initialize display
            updatePriceDisplay();
            checkActivePreset();
            updatePriceRange();
        });
    </script>
