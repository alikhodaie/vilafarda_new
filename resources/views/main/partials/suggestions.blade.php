<link rel="stylesheet" href="{{ asset('assets/css/last-minute-off.css') }}">
<link rel="stylesheet" href="{{ public_asset_version('assets/css/suggestions.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}" />

<section id="suggestions-section" class="index-section suggestions-section">
    <div class="index-section__header">
        <h2 class="index-section__title">پیشنهادات ما</h2>
    </div>
    <p id="suggestion-section-description" class="index-section__description d-none"></p>

    <div class="tab-bar-scroll suggestion-tabs mb-2" role="tablist">
        @foreach($categories as $cat)
            <button type="button"
                    class="badge bg-white border text-dark suggestion-badge mx-1 py-2 px-3 @if($loop->first) active @endif"
                    data-value="{{ $cat['slug'] }}"
                    data-more-url="{{ $cat['more_url'] }}"
                    data-description="{{ indexHomeCategoryDescription($cat['slug']) }}"
                    role="tab"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                {{ $cat['title'] }}
            </button>
        @endforeach
    </div>

    <div class="swiper suggestions-swiper">
        <div class="swiper-wrapper" id="suggestion-homes-list"></div>
    </div>
</section>

<script>
    window.suggestionInitialHomes = @json(($suggestionInitialHomes ?? collect())->values());
    window.suggestionInitialSlug = @json($categories[0]['slug'] ?? null);
</script>
<script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ public_asset_version('assets/js/guest-rating.js') }}"></script>
<script src="{{ public_asset_version('assets/js/seo-image-utils.js') }}"></script>
<script src="{{ public_asset_version('assets/js/suggestions.js') }}"></script>
