@extends('layouts.main.main', ['title' => __('title.blog')])

@section('content')
    <!-- ============================ Page Title Start================================== -->
    <div class="page-title" style="background:#f4f4f4 url(assets/img/slider-3.jpg);" data-overlay="5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">

                    <div class="breadcrumbs-wrap">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">@lang('title.blog')</li>
                        </ol>
                        <h2 class="breadcrumb-title">وبلاگ شبکه ای - وبلاگ های ما</h2>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- ============================ Page Title End ================================== -->

    <!-- ============================ Agency List Start ================================== -->
    <section class="gray">

        <div class="container">

            <div class="row">
                <div class="col text-center">
                    <div class="sec-heading center">
                        <h2>آخرین اخبار و مقالات</h2>
                        <p>ما به طور منظم قوی ترین مقالات را برای کمک و پشتیبانی ارسال می کنیم.</p>
                    </div>
                </div>
            </div>

            <!-- row Start -->
            <div class="row">

                <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                    @if($articles->isNotEmpty())
                        <div class="row">
                            @foreach($articles as $article)
                                <!-- Single blog Grid -->
                                <div class="col-12 col-lg-6">
                                    @include('main.articles.partials.article-card', compact('article'))
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-danger text-center">@lang('text.empty search')</div>
                    @endif

                    <!-- Pagination -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            {{ $articles->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <x-blog.sidebar></x-blog.sidebar>
                </div>

            </div>
            <!-- /row -->

        </div>

    </section>
    <!-- ============================ Agency List End ================================== -->
@endsection
