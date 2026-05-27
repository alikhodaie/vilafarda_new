@extends('layouts.main.main', ['title' => setting('about-us:page-title')])

@section('content')
    <!-- ============================ Page Title Start================================== -->
    <div class="page-title" style="background:#f4f4f4 url({{ settingFilePath('about-us:banner') }});" data-overlay="5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">

                    <div class="breadcrumbs-wrap">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">{{ setting('about-us:page-title') }}</li>
                        </ol>
                        <h2 class="breadcrumb-title">{{ setting('about-us:title') }}</h2>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- ============================ Page Title End ================================== -->

    <!-- ============================ Our Story Start ================================== -->
    <section>

        <div class="container">

            <!-- row Start -->
            <div class="row align-items-center">

                <div class="col-lg-6 col-md-6">
                    <div class="story-wrap explore-content">

                        <h2>{{ setting('about-us:story-title') }}</h2>
                        <span class="theme-cl">{{ setting('about-us:story-title1') }}</span>
                        <p class="mt-4">{!! setting('about-us:story-description') !!}</p>
                        <a href="{{ setting('about-us:story-btn-link') }}" class="btn theme-bg btn-rounded">{{ setting('about-us:story-btn-title') }}</a>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <img src="{{ settingFilePath('about-us:story-image') }}" class="img-fluid rounded" alt="" />
                </div>

            </div>
            <!-- /row -->

        </div>

    </section>
    <!-- ============================ Our Story End ================================== -->

    <!-- ============================ Our Counter Start ================================== -->
    <section class="image-cover" style="background:#D39D1A url({{ asset('assets/img/pattern.png') }}) no-repeat;">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-10 col-md-12 col-sm-12">
                    <div class="text-center mb-5">
                        <span class="text-light">{{ setting('about-us:reward-title') }}</span>
                        <h2 class="font-weight-normal text-light">{{ setting('about-us:reward-description') }}</h2>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="_morder_counter">
                        <div class="_morder_counter_thumb"><i class="ti-cup"></i></div>
                        <div class="_morder_counter_caption">
                            <h5 class="text-light"><span>{{ setting('about-us:reward-box1-count') }}</span></h5>
                            <span class="text-light">{{ setting('about-us:reward-box1-title') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="_morder_counter">
                        <div class="_morder_counter_thumb"><i class="ti-briefcase"></i></div>
                        <div class="_morder_counter_caption">
                            <h5 class="text-light"><span>{{ setting('about-us:reward-box2-count') }}</span></h5>
                            <span class="text-light">{{ setting('about-us:reward-box2-title') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="_morder_counter">
                        <div class="_morder_counter_thumb"><i class="ti-light-bulb"></i></div>
                        <div class="_morder_counter_caption">
                            <h5 class="text-light"><span>{{ setting('about-us:reward-box3-count') }}</span></h5>
                            <span class="text-light">{{ setting('about-us:reward-box3-title') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="_morder_counter">
                        <div class="_morder_counter_thumb"><i class="ti-heart"></i></div>
                        <div class="_morder_counter_caption">
                            <h5 class="text-light"><span>{{ setting('about-us:reward-box4-count') }}</span></h5>
                            <span class="text-light">{{ setting('about-us:reward-box4-title') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- ============================ Our Counter End ================================== -->

    @php($comments = json_decode(setting('about-us:comments'), true) ?: [])
    @if(! empty($comments))
        <!-- ============================ Smart Testimonials ================================== -->
        <section class="gray-simple">
            <div class="container">

                <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-8">
                        <div class="sec-heading center">
                            <h2>{{ setting('about-us:comments-title') }}</h2>
                            <p>{{ setting('about-us:comments-description') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="item-slide space">
                            @foreach($comments as $comment)
                                <!-- Single Item -->
                                <div class="single_items">
                                    <div class="_testimonial_wrios">
                                        <div class="d-flex justify-content-between">
                                            <div class="_tsl_flex_capst">
                                                <h5>{{ $comment['name'] }}</h5>
                                                <div class="_ovr_posts my-1"><span>{{ $comment['job'] }}</span></div>
                                            </div>
                                            <div class="_ovr_rates"><span><i class="fa fa-star"></i></span>{{ $comment['score'] }}</div>
                                        </div>

                                        <div class="facts-detail">
                                            <p>{{ $comment['description'] }}</p>
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

    <!-- ============================ article Start ================================== -->
    <section class="min">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-8">
                    <div class="sec-heading center">
                        <h2>{{ setting('about-us:articles-title') }}</h2>
                        <p>{{ setting('about-us:articles-description') }}</p>
                    </div>
                </div>
            </div>

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
@endsection
