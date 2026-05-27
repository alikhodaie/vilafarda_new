@extends('layouts.main.main', ['title' => setting('contact-us:title')])

@section('content')
    <!-- ============================ Page Title Start================================== -->
    <div class="page-title" style="background:#f4f4f4 url({{ settingFilePath('contact-us:banner') }});" data-overlay="5">
        <div class="ht-80"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="_page_tetio">
                        <div class="pledtio_wrap"><span>{{ setting('contact-us:title') }}</span></div>
                        <h2 class="text-light mb-0">{{ setting('contact-us:description1') }}</h2>
                        <p>{{ setting('contact-us:description2') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="ht-120"></div>
    </div>
    <!-- ============================ Page Title End ================================== -->

    <!-- ============================ Agency List Start ================================== -->
    <section class="pt-0">
        <div class="container">
            <div class="row align-items-center pretio_top">

                @if(setting('contact-us:box1-title') || setting('contact-us:box1-email') || setting('contact-us:box1-phone'))
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="contact-box">
                            <i class="ti-shopping-cart theme-cl"></i>
                            <h4>{{ setting('contact-us:box1-title') }}</h4>
                            <p><a href="mailto:{{ setting('contact-us:box1-email') }}">{{ setting('contact-us:box1-email') }}</a></p>
                            <span><a href="tel:{{ setting('contact-us:box1-phone') }}">{{ setting('contact-us:box1-phone') }}</a></span>
                        </div>
                    </div>
                @endif

                @if(setting('contact-us:box2-title') || setting('contact-us:box2-email') || setting('contact-us:box2-phone'))
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="contact-box">
                            <i class="ti-user theme-cl"></i>
                            <h4>{{ setting('contact-us:box2-title') }}</h4>
                            <p><a href="mailto:{{ setting('contact-us:box2-email') }}">{{ setting('contact-us:box2-email') }}</a></p>
                            <span><a href="tel:{{ setting('contact-us:box2-phone') }}">{{ setting('contact-us:box2-phone') }}</a></span>
                        </div>
                    </div>
                @endif

                @if(setting('contact-us:box3-title') || setting('contact-us:box3-email') || setting('contact-us:box3-phone'))
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="contact-box">
                            <i class="ti-comment-alt theme-cl"></i>
                            <h4>{{ setting('contact-us:box3-title') }}</h4>
                            <p><a href="mailto:{{ setting('contact-us:box3-email') }}">{{ setting('contact-us:box3-email') }}</a></p>
                            <span><a href="tel:{{ setting('contact-us:box3-phone') }}">{{ setting('contact-us:box3-phone') }}</a></span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- row Start -->
            <div class="row">
                <div class="col-lg-8 col-md-7">
                    <div class="property_block_wrap">
                        <div class="property_block_wrap_header">
                            <h4 class="property_block_title">@lang('title.fill_form')</h4>
                        </div>

                        @include('main.partials.contact-form')
                    </div>
                </div>

                @if(setting('contact-us:map-iframe'))
                    <div class="col-lg-4 col-md-5">
                        {!! setting('contact-us:map-iframe') !!}
                    </div>
                @endif
            </div>
            <!-- /row -->
        </div>
    </section>
    <!-- ============================ Agency List End ================================== -->

    @if($articles->isNotEmpty())
    <!-- ============================ article Start ================================== -->
    <section class="min">
        <div class="container">

            @if(setting('contact-us:article-title') || setting('contact-us:article-description'))
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-8">
                        <div class="sec-heading center">
                            <h2>{{ setting('contact-us:article-title') }}</h2>
                            <p>{!! setting('contact-us:article-description') !!}</p>
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
