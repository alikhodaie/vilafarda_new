@if(!empty($similarCategories))
    @php $similarLayout = $layout ?? 'mobile'; @endphp

    <link rel="stylesheet" href="{{ asset('assets/css/suggestions.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/last-minute-off.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/home-similar-homes.css') }}">

    @if($similarLayout === 'mobile')
        <hr class="home-detail-divider">
    @endif

    <section id="similar-homes-section" class="@if($similarLayout === 'mobile') home-detail-section @endif home-similar-homes mb-4">
        @if($similarLayout === 'desktop')
            <div class="property_block_wrap_header mb-2">
                <h4 class="property_block_title">اقامتگاه‌های مشابه</h4>
            </div>
        @else
            <h3 class="home-detail-section__title">
                <i class="bi bi-houses"></i>
                اقامتگاه‌های مشابه
            </h3>
        @endif

        <div class="home-similar-tabs tab-bar-scroll suggestion-tabs mb-2" role="tablist">
            @foreach($similarCategories as $category)
                <button type="button"
                        class="badge bg-white border text-dark suggestion-badge home-similar-tab mx-1 py-2 px-3 @if($loop->first) active @endif"
                        data-value="{{ $category['slug'] }}"
                        data-more-url="{{ $category['more_url'] }}"
                        role="tab"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $category['title'] }}
                </button>
            @endforeach
        </div>

        <div class="swiper home-similar-swiper">
            <div class="swiper-wrapper" id="similar-homes-list"></div>
        </div>
    </section>

    <script type="application/json" id="similarHomesData">@json(['homes' => $similarHomesByGroup])</script>
    <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/guest-rating.js') }}"></script>
    <script src="{{ public_asset_version('assets/js/seo-image-utils.js') }}"></script>
    <script src="{{ public_asset_version('assets/js/similar-homes.js') }}"></script>
@endif
