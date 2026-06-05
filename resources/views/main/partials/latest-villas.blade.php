@php
    $sectionTitle = $sectionTitle ?? (setting('index:home-tomorrow-order-title') ?: 'ویلافردا');
    $sectionMoreUrl = $sectionMoreUrl ?? route('main.homes.index', ['filter' => 'open_tomorrow']);
    $sectionDescription = $sectionDescription ?? indexHomeCategoryDescription('open-tomorrow');
    $showDesktopNav = $showDesktopNav ?? false;
@endphp

<link rel="stylesheet" href="{{ public_asset_version('assets/css/discounted-villas.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}" />

<section class="index-section" id="open-tomorrow-section" aria-labelledby="open-tomorrow-heading">
    <div class="index-section__header">
        <h2 class="index-section__title" id="open-tomorrow-heading">{{ $sectionTitle }}</h2>
        <a href="{{ $sectionMoreUrl }}" class="index-section__more text-decoration-none">مشاهده همه</a>
    </div>
    @if($sectionDescription !== '')
        <p class="index-section__description">{{ $sectionDescription }}</p>
    @endif
    <div class="index-swiper-wrap index-swiper-wrap--overlay-nav @if($showDesktopNav) index-swiper-wrap--with-nav @endif" data-index-swiper="open-tomorrow">
        <div class="swiper discounted-villas-swiper">
            <div class="swiper-wrapper" id="discounted-villas-list"></div>
        </div>
        @if($showDesktopNav)
            @include('main.partials.index-swiper-nav', ['navId' => 'open-tomorrow'])
        @endif
    </div>
</section>

<script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/index-section-visibility.js') }}"></script>
@if($showDesktopNav)
    <script src="{{ public_asset_version('assets/js/index-swiper-nav.js') }}"></script>
@endif
<script src="{{ public_asset_version('assets/js/seo-image-utils.js') }}"></script>
<script src="{{ public_asset_version('assets/js/latest-villas.js') }}"></script>
