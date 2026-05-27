<link rel="stylesheet" href="{{ asset('assets/css/suggestions.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/last-minute-off.css') }}">

<section class="index-section" id="suggestions-section">
    <div class="index-section__header">
        <span class="index-section__title">پیشنهادات ما</span>
    </div>

    <div class="tab-bar-scroll suggestion-tabs mb-2" role="tablist">
        @foreach($categories as $cat)
            <span class="badge bg-white border text-dark suggestion-badge mx-1 py-2 px-3 @if($loop->first) active @endif"
                  data-value="{{ $cat['slug'] }}"
                  data-more-url="{{ $cat['more_url'] }}"
                  role="tab"
                  aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $cat['title'] }}</span>
        @endforeach
    </div>

    <div class="swiper suggestions-swiper">
        <div class="swiper-wrapper" id="suggestion-homes-list"></div>
    </div>
</section>

<script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/index-section-visibility.js') }}"></script>
<script src="{{ asset('assets/js/guest-rating.js') }}"></script>
<script src="{{ public_asset_version('assets/js/seo-image-utils.js') }}"></script>
<script src="{{ public_asset_version('assets/js/suggestions.js') }}"></script>
