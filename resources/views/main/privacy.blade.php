@extends('layouts.main.main', ['title' => setting('privacy:title')])

@section('content')
    <!-- ============================ Page Title Start================================== -->
    <div class="page-title" style="background:#f4f4f4 url({{ settingFilePath('privacy:banner') }});" data-overlay="5">
        <div class="ht-80"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="_page_tetio">
                        <div class="pledtio_wrap"><span>{{ setting('privacy:title') }}</span></div>
                        <h2 class="text-light mb-0">{{ setting('privacy:description1') }}</h2>
                        <p>{{ setting('privacy:description2') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="ht-120"></div>
    </div>
    <!-- ============================ Page Title End ================================== -->

    <section>
        <div class="container">
            {!! setting('privacy:content') !!}
        </div>
    </section>

    @if($articles->isNotEmpty())
    <!-- ============================ article Start ================================== -->
    <section class="min">
        <div class="container">

            @if(setting('privacy:article-title') || setting('privacy:article-description'))
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-8">
                        <div class="sec-heading center">
                            <h2>{{ setting('privacy:article-title') }}</h2>
                            <p>{!! setting('privacy:article-description') !!}</p>
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
