<!-- SwiperJS CDN -->
<link rel="stylesheet" href="{{ asset('assets/css/discounted-villas.css') }}">

<link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}" />
<script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>



<section class="index-section">
    <div class="index-section__header">
        <span class="index-section__title">ویلافردا</span>
        <a href="/homes" class="text-decoration-none black-outline-border" style="color:#343434;">مشاهده همه</a>
    </div>
    <div class="swiper discounted-villas-swiper">
        <div class="swiper-wrapper" id="discounted-villas-list">
            <!-- Villas will be injected here by JS -->
        </div>
    </div>
</section>
<script src="{{ asset('assets/js/index-section-visibility.js') }}"></script>
<script src="{{ public_asset_version('assets/js/seo-image-utils.js') }}"></script>
<script src="{{ public_asset_version('assets/js/latest-villas.js') }}"></script>