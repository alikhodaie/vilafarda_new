@extends('layouts.main.main')

@section('top-assets')
    <link href="{{ public_asset_version('assets/css/index-desktop.css') }}" rel="stylesheet">
    <link href="{{ public_asset_version('assets/css/home-favorite.css') }}" rel="stylesheet">
    <link href="{{ public_asset_version('assets/css/last-minute-off.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}" />
@endsection

@section('bottom-assets')
    <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ public_asset_version('assets/js/index-swiper-nav.js') }}"></script>
    <script>
        (function () {
            function bootCategorySwipers() {
                if (window.IndexSwiperNav && typeof window.IndexSwiperNav.initCategorySwipers === 'function') {
                    return window.IndexSwiperNav.initCategorySwipers();
                }

                if (typeof window.initIndexCategorySwipers === 'function') {
                    return window.initIndexCategorySwipers();
                }

                return false;
            }

            bootCategorySwipers();
            window.setTimeout(bootCategorySwipers, 0);
            window.setTimeout(bootCategorySwipers, 400);
            window.setTimeout(bootCategorySwipers, 1000);
        })();
    </script>
@endsection

@section('content')
<main id="main-content">
    <!-- ============================ Hero Banner  Start================================== -->
    <div class="index-hero">
        @if(($bannerType ?? indexBannerType()) === 'video')
            <div class="hero-banner vedio-banner index-hero__media">
                <div class="overlay"></div>

                <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
                    <source src="{{ settingFilePath('index:banner-video') }}" type="video/mp4">
                </video>

                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <h1 class="big-header-capt mb-0 text-light">{{ setting('index:banner-title') }}</h1>
                            <p class="text-center mb-0 text-light index-hero__caption">{{ setting('index:banner-description') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(($bannerType ?? indexBannerType()) === 'slider' && ! empty($slider))
            <h1 class="sr-only">{{ setting('index:banner-title') ?: indexPageTitleSegment() }}</h1>
            <div class="index-hero__media">
                <div class="container d-block d-lg-none">
                    <landing-slider
                        per_view="1"
                        :items="{{ json_encode($slider) }}"
                    ></landing-slider>
                </div>
                <div class="container-fluid d-none d-lg-block p-0">
                    <landing-slider
                        per_view="1"
                        :items="{{ json_encode($slider) }}"
                    ></landing-slider>
                </div>
            </div>
        @endif

        <div class="index-hero__search">
            @include('main.partials.search-box-desktop', ['onBanner' => true])
        </div>
    </div>
    <!-- ============================ Hero Banner End ================================== -->

    @if($showOpenTomorrow ?? false)
        @include('main.partials.latest-villas', ['showDesktopNav' => true])
    @endif

    @if($showOffHomes ?? false)
        @include('main.partials.last-minute-off', [
            'offCities' => $offCities ?? [],
            'offHomesInitial' => $offHomesInitial ?? collect(),
            'showDesktopNav' => true,
        ])
    @endif

    @include('main.partials.nearby-map-banner')

        <!-- ============================ Property Location ================================== -->
        <section class="min">
            <div class="container">

         @if(! empty($cities))       <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-8">
                        <div class="sec-heading center">
                            <h2>{{ setting('index:position-title') }}</h2>
                            <p>{{ setting('index:position-description') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="d-block d-lg-flex" style="overflow-x: auto; overflow-y: hidden; white-space: nowrap;">
                            @foreach($cities as $item)
                                <a class="slide-item d-inline-block d-lg-block img-wrap style-2 mx-3" href="{{ route('main.homes.index', ['province' => $item['province']['id'], 'city' => $item['city']['id']]) }}" aria-label="اقامتگاه‌های {{ $item['city']['name'] }}، {{ $item['province']['name'] }}">
                                    <div class="location_wrap_content visible">
                                        <div class="location_wrap_content_first">
                                            <h3 class="h5 mb-0">{{ $item['province']['name'] }}, {{ $item['city']['name'] }}</h3>
                                            <ul>
                                                <li><span>{{ number_format($item['count_homes']) }} @lang('title.home')</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="img-wrap-background" style="background-image: url({{ $item['image'] }});"></div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- ============================ Property Location End ================================== -->
    @endif

{{--    <div class="mt-5">--}}
{{--        @include('main.partials.index-homes', [--}}
{{--            'link' => route('main.homes.index', ['sort' => 'open_now']),--}}
{{--            'title' => setting('index:home-ready-order-title'),--}}
{{--            'description' => setting('index:home-ready-order-description'),--}}
{{--            'homes' => $open_homes,--}}
{{--            'is_today' => true--}}
{{--        ])--}}
{{--    </div>--}}

    @include('main.partials.index-category-homes', [
        'sectionId' => 'index-cheap-homes',
        'link' => route('main.homes.index', ['sort' => 'cheap']),
        'title' => setting('index:home-cheap-title'),
        'description' => setting('index:home-cheap-description'),
        'homes' => $cheap_homes,
        'showDesktopNav' => true,
    ])

    @include('main.partials.index-category-homes', [
        'sectionId' => 'index-popular-homes',
        'link' => route('main.homes.index', ['sort' => 'popular']),
        'title' => setting('index:home-popular-title'),
        'description' => setting('index:home-popular-description'),
        'homes' => $popular_homes,
        'showDesktopNav' => true,
    ])

    @include('main.partials.index-category-homes', [
        'sectionId' => 'index-latest-homes',
        'link' => route('main.homes.index', ['sort' => 'latest']),
        'title' => setting('index:home-latest-title'),
        'description' => setting('index:home-latest-description'),
        'homes' => $last_homes,
        'showDesktopNav' => true,
    ])

    @include('main.partials.index-category-homes', [
        'sectionId' => 'index-expensive-homes',
        'link' => route('main.homes.index', ['sort' => 'expensive']),
        'title' => setting('index:home-expensive-title'),
        'description' => setting('index:home-expensive-description'),
        'homes' => $expensive_homes,
        'showDesktopNav' => true,
    ])

    @if($consultants->isNotEmpty())
        <!-- ============================ Top Agents ================================== -->
        <section class="gray-simple min">
            <div class="container">

                <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-8">
                        <div class="sec-heading center">
                            <h2>{{ setting('index:consultant-title') }}</h2>
                            <p>{{ setting('index:consultant-description') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="item-slide space">

                        @foreach($consultants as $consultant)
                            <!-- Single Item -->
                                <div class="single_items">
                                    <div class="grid_agents">
                                        <div class="grid_agents-wrap">

                                            <div class="fr-grid-thumb">
                                                <h4>
                                                    <span class="verified"><img src="{{ asset('assets/img/verified.svg') }}" class="verify mx-auto" alt=""></span>
                                                    <img src="{{ $consultant->image_path }}" class="img-fluid mx-auto" alt="{{ $consultant->name }}" onerror="this.src='{{ asset('assets/images/avatar.jpg') }}'">
                                                </h4>
                                            </div>

                                            <div class="fr-grid-deatil">
                                                <span><i class="ti-location-pin ml-1"></i>{{ $consultant->province->name }} ، {{ $consultant->city->name }}</span>
                                                <h5 class="fr-can-name">{{ $consultant->name }}</h5>
                                            </div>

                                            <div class="fr-infos-deatil">
                                                @if($consultant->whatsapp_number)
                                                    <a target="_blank" href="https://api.whatsapp.com/send?phone={{ $consultant->whatsapp_number }}&text={{ $consultant->whatsapp_default_text }}" class="btn agent-btn theme-bg"><i class="fab fa-whatsapp ml-2"></i>@lang('title.send_message')</a>
                                                @endif
                                                @if($consultant->phone_number)
                                                    <a href="tel:{{ $consultant->phone_number }}" class="btn agent-btn theme-black"><i class="fa fa-phone"></i></a>
                                                @endif
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- ============================ Top Agents End ================================== -->
    @endif

    @if($comments->isNotEmpty())
        <!-- ============================ Smart Testimonials ================================== -->
        <section class="image-cover index-testimonials-section" style="background:#122947 url({{ asset('assets/img/pattern.png') }}) no-repeat;">
            <div class="container">

                <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-8">
                        <div class="sec-heading center light">
                            <h2>{{ setting('index:comments-title') }}</h2>
                            <p>{{ setting('index:comments-description') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-8">
                        <div class="modern-testimonial">

                            @foreach($comments as $comment)
                                <!-- Single Items -->
                                <div class="single_items">
                                    <div class="_smart_testimons">
                                        <div class="_smart_testimons_thumb">
                                            <img src="{{ $comment->user->avatar_path }}" class="img-fluid rounded-circle" alt="{{ $comment->full_name }}"
                                                 onerror="this.onerror=null;this.src='{{ \App\Models\User::getDefaultAvatar() }}'">
                                            <span class="tes_quote"><i class="fa fa-quote-left"></i></span>
                                        </div>
                                        <div class="facts-detail">
                                            <blockquote class="mb-0">
                                                <p>{{ $comment->comment }}</p>
                                            </blockquote>
                                        </div>
                                        <div class="_smart_testimons_info">
                                            <cite class="index-testimonial__author">{{ $comment->full_name }}</cite>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- ============================ Smart Testimonials End ================================== -->
    @endif

    @if($articles->isNotEmpty())
        <!-- ============================ article Start ================================== -->
        <section class="min">
            <div class="container">

                @if(setting('index:articles-title') || setting('index:articles-description'))
                    <div class="row justify-content-center">
                        <div class="col-lg-7 col-md-8">
                            <div class="sec-heading center">
                                <h2>{{ setting('index:articles-title') }}</h2>
                                <p>{!! setting('index:articles-description') !!}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                @foreach($articles as $blogArticle)
                    <!-- Single blog Grid -->
                        <div class="col-lg-4 col-md-6 d-flex">
                            @include('main.articles.partials.article-card', ['article' => $blogArticle])
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
        <div class="clearfix"></div>
        <!-- ============================ article End ================================== -->
    @endif
</main>
@endsection
