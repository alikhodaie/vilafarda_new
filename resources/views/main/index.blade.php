@extends('layouts.main.main', ['title' => setting('index:page-title')])

@section('content')
    <!-- ============================ Hero Banner  Start================================== -->
    @if(($bannerType ?? indexBannerType()) === 'video')
    <div class="hero-banner vedio-banner">
        <div class="overlay"></div>
        
        <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
            <source src="{{ settingFilePath('index:banner-video') }}" type="video/mp4">
        </video>

        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <h1 class="big-header-capt mb-0 text-light">{{ setting('index:banner-title') }}</h1>
                    <p class="text-center mb-4 text-light">{{ setting('index:banner-description') }}</p>
                </div>
            </div>

            @include('main.partials.index-advanced-filter')
        </div>
    </div>
    @endif
    @if(($bannerType ?? indexBannerType()) === 'slider' && ! empty($slider))
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
        <div class="container">
            @include('main.partials.index-advanced-filter')
        </div>
    @endif
    <!-- ============================ Hero Banner End ================================== -->

{{--    @include('main.partials.category-icons')--}}
    @if($off_homes->isNotEmpty())
    <div style="background-color: rgb(211, 157, 26)">
        @include('main.partials.index-homes', [
            'link' => route('main.homes.index', ['filter' => 'off']),
            'title' => setting('index:home-off-title'),
            'description' => setting('index:home-off-description'),
            'homes' => $off_homes,
            'is_off' => true
        ])
    </div>
    @endif

    
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
                                <a class="slide-item d-inline-block d-lg-block img-wrap style-2 mx-3" href="{{ route('main.homes.index', ['province' => $item['province']['id'], 'city' => $item['city']['id']]) }}">
                                    <div class="location_wrap_content visible">
                                        <div class="location_wrap_content_first">
                                            <h4>{{ $item['province']['name'] }}, {{ $item['city']['name'] }}</h4>
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

    @include('main.partials.index-homes', [
        'link' => route('main.homes.index', ['filter' => 'open_tomorrow']),
        'title' => setting('index:home-tomorrow-order-title'),
        'description' => setting('index:home-tomorrow-order-description'),
        'homes' => $open_tomorrow,
        'is_tomorrow' => true
    ])

    @include('main.partials.index-homes', [
        'link' => route('main.homes.index', ['sort' => 'cheap']),
        'title' => setting('index:home-cheap-title'),
        'description' => setting('index:home-cheap-description'),
        'homes' => $cheap_homes
    ])

    @include('main.partials.index-homes', [
        'link' => route('main.homes.index', ['sort' => 'popular']),
        'title' => setting('index:home-popular-title'),
        'description' => setting('index:home-popular-description'),
        'homes' => $popular_homes
    ])

    @include('main.partials.index-homes', [
        'link' => route('main.homes.index', ['sort' => 'latest']),
        'title' => setting('index:home-latest-title'),
        'description' => setting('index:home-latest-description'),
        'homes' => $last_homes
    ])

    @include('main.partials.index-homes', [
        'link' => route('main.homes.index', ['sort' => 'expensive']),
        'title' => setting('index:home-expensive-title'),
        'description' => setting('index:home-expensive-description'),
        'homes' => $expensive_homes
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
                                                    <img src="{{ $consultant->image_path }}" class="img-fluid mx-auto" alt="" onerror="this.src='{{ asset('assets/images/avatar.jpg') }}'">
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
        <section class="image-cover" style="background:#122947 url({{ asset('assets/img/pattern.png') }}) no-repeat;">
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
                                            <p>{{ $comment->comment }}</p>
                                        </div>
                                        <div class="_smart_testimons_info">
                                            <h5>{{ $comment->full_name }}</h5>
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
                @foreach($articles as $article)
                    <!-- Single blog Grid -->
                        <div class="col-lg-4 col-md-6">
                            @include('main.articles.partials.article-card', compact('article'))
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
        <div class="clearfix"></div>
        <!-- ============================ article End ================================== -->
    @endif
@endsection
