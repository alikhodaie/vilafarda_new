@extends('layouts.main.main', ['title' => __('title.homes'), 'has_footer' => false])

@section('meta')
    @include('main.homes.partials.homes-seo-pagination-meta')
@endsection

@section('top-assets')
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/map-travel-sheet.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/mobile-price-range.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/home-favorite.css') }}" rel="stylesheet">
    <link href="{{ public_asset_version('assets/css/homes-desktop.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/dist/leaflet.css') }}" />
@endsection

@section('content')
    <div class="homes-desktop-page">
        <div class="homes-desktop-header">
            <div class="homes-desktop-header__inner">
                <div class="homes-desktop-header__title-row">
                    <div>
                        <h1 class="homes-desktop-header__title">جستجوی اقامتگاه</h1>
                        <p class="homes-desktop-header__subtitle">پیدا کردن اقامتگاه مناسب</p>
                    </div>
                </div>
                @include('main.homes.partials.homes-filter-section')
            </div>
        </div>

        <div class="homes-desktop-split">
            <div class="homes-desktop-list-panel">
                <div class="homes-desktop-list-inner">
                    <div class="homes-desktop-results-header">
                        <div>
                            <p class="homes-desktop-results-count">
                                @if($homes->total() > 0)
                                    {{ persianNumber($homes->total()) }} اقامتگاه
                                    @if($min)
                                        از {{ persianNumber($min) }} تومان
                                    @endif
                                @else
                                    نتیجه‌ای یافت نشد
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($homes->count() > 0)
                        <div class="homes-desktop-cards-grid">
                            @foreach($homes as $home)
                                @include('main.homes.partials.mobile-home-card', ['home' => $home])
                            @endforeach
                        </div>

                        @if($homes->hasPages())
                            <div class="homes-desktop-pagination">
                                @include('main.homes.partials.homes-pagination')
                            </div>
                        @endif
                    @else
                        <div class="homes-desktop-empty">
                            <i class="bi bi-house fs-1 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">@lang('text.empty search')</h5>
                            <p class="text-muted mb-3" style="font-size: 14px;">
                                متأسفانه اقامتگاهی با این شرایط پیدا نشد
                            </p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal" style="background: #D39D1A; border-color: #D39D1A; border-radius: 12px;">
                                <i class="bi bi-funnel me-2"></i>
                                تغییر فیلترها
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="homes-desktop-map-panel">
                <div class="homes-desktop-map-shell">
                    <div id="desktopHomesMap"></div>

                    <div class="homes-desktop-map-controls">
                        <button type="button" class="homes-desktop-map-control-btn" id="desktopMapZoomInBtn" aria-label="بزرگ‌نمایی">+</button>
                        <button type="button" class="homes-desktop-map-control-btn" id="desktopMapZoomOutBtn" aria-label="کوچک‌نمایی">−</button>
                        <button type="button" class="homes-desktop-map-control-btn" id="desktopMapMyLocationBtn" aria-label="موقعیت من">
                            <i class="bi bi-crosshair"></i>
                        </button>
                    </div>

                    <div id="desktopMapPropertyPreview" class="homes-desktop-map-preview" style="display: none;">
                        <button type="button" class="homes-desktop-map-preview-close" id="desktopMapPreviewCloseBtn" aria-label="بستن">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <a href="#" id="desktopMapPreviewLink" class="homes-desktop-map-preview-link">
                            <img src="" alt="" id="desktopMapPreviewImage" class="homes-desktop-map-preview-thumb">
                            <div class="homes-desktop-map-preview-body">
                                <h6 class="homes-desktop-map-preview-title" id="desktopMapPreviewTitle"></h6>
                                <p class="homes-desktop-map-preview-meta" id="desktopMapPreviewMeta"></p>
                                <p class="homes-desktop-map-preview-price" id="desktopMapPreviewPrice"></p>
                                <span class="homes-desktop-map-preview-badge" id="desktopMapPreviewBadge" style="display: none;"></span>
                            </div>
                        </a>
                    </div>

                    <p class="homes-desktop-map-summary" id="desktopMapResultsSummary">در حال بارگذاری...</p>
                </div>
            </div>
        </div>
    </div>

    @include('main.homes.partials.homes-filter-modals')
    @include('main.homes.partials.homes-travel-filter-sheets')
@endsection

@section('bottom-assets')
    <script>
        window.homesDateFilterConfig = {
            minDate: @json(\App\Models\Order::getMinReserveDate()->format('Y-m-d')),
            maxDate: @json(\App\Models\Order::getMaxReserveDate()->format('Y-m-d')),
            startLabel: @json(__('title.date_enter')),
            endLabel: @json(__('title.date_quit')),
        };
        window.provinceMapCenters = @json($provinceMapCenters ?? []);
        window.homesDesktopMapConfig = {
            mapDataUrl: @json(route('main.homes.map-data')),
        };
    </script>
    <script src="{{ asset('vendor/leaflet/dist/leaflet.js') }}"></script>
    <script src="{{ asset('assets/js/guest-rating.js') }}"></script>
    <script src="{{ asset('assets/js/map-travel-jalali-calendar.js') }}"></script>
    <script src="{{ asset('assets/js/homes-mobile-search.js') }}"></script>
    <script src="{{ asset('assets/js/mobile-price-range.js') }}"></script>
    <script src="{{ asset('assets/js/map-travel-filter.js') }}"></script>
    <script src="{{ asset('assets/js/homes-filters.js') }}"></script>
    <script src="{{ asset('assets/js/homes-date-filter.js') }}"></script>
    <script src="{{ public_asset_version('assets/js/homes-desktop-map.js') }}"></script>
@endsection
