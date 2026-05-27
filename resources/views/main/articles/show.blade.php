@extends('layouts.main.main', ['title' => $article->title])

@section('content')
    <!-- ============================ Page Title Start================================== -->
    <div class="page-title" style="background:#f4f4f4 url({{ asset('assets/img/slider-2.jpg') }});" data-overlay="5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">

                    <div class="breadcrumbs-wrap">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">جزئیات وبلاگ</li>
                        </ol>
                        <h2 class="breadcrumb-title">{{ $article->title }}</h2>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- ============================ Page Title End ================================== -->

    <!-- ============================ Agency List Start ================================== -->
    <section class="gray">

        <div class="container">

            <!-- row Start -->
            <div class="row">

                <!-- Blog Detail -->
                <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="article_detail_wrapss single_article_wrap format-standard">
                        <div class="article_body_wrap">

                            <div class="article_featured_image">
                                <img class="img-fluid" src="{{ $article->image_path }}" alt="{{ $article->title }}">
                            </div>

                            <div class="article_top_info">
                                <ul class="article_middle_info">
                                    <li><a href="{{ route('main.articles.index', ['author' => $article->author_id]) }}"><span class="icons"><i class="ti-user"></i></span>@lang('title.author'): {{ $article->author->full_name }}</a></li>
                                    <li><a href="#comments"><span class="icons"><i class="ti-comment-alt"></i></span>@lang('title.comments') {{ $article->count_comments }}</a></li>
                                </ul>
                            </div>
                            <h2 class="post-title">{{ $article->title }}</h2>
                            <p>{!! $article->content !!}</p>
                        </div>
                    </div>

                    <!-- Blog Tags -->
                    <div class="single_widgets widget_tags">
                        <ul>
                            @foreach($article->tags as $tag)
                                <li><a href="{{ route('main.articles.index', ['tag' => $tag->id]) }}">{{ $tag->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Author Detail -->
                    <a href="{{ route('main.articles.index', ['author' => $article->author_id]) }}" class="article_detail_wrapss single_article_wrap format-standard">

                        <div class="article_posts_thumb">
                            <span class="img"><img class="img-fluid rounded-circle" src="{{ $article->author->avatar_path }}" alt="{{ $article->author->full_name }}"></span>
                            <h3 class="pa-name">{{ $article->author->full_name }}</h3>
                        </div>

                    </a>

                    <!-- Blog Comment -->
                    <div id="comments" class="article_detail_wrapss single_article_wrap format-standard">

                        <div class="comment-area">
                            <div class="all-comments">
                                <h3 class="comments-title">{{ $article->count_comments }} @lang('title.comments')</h3>
                                <div class="comment-list">
                                    <ul>
                                        @foreach($article->activeComments as $comment)
                                            <li class="article_comments_wrap">
                                                <article>
                                                    @if($comment->user)
                                                        <div class="article_comments_thumb">
                                                            <img width="60" height="60" src="{{ $comment->user->avatar_path }}" alt="{{ $comment->full_name }}" class="rounded-circle">
                                                        </div>
                                                    @endif
                                                    <div class="comment-details">
                                                        <div class="comment-meta">
                                                            <div class="comment-left-meta">
                                                                <h4 class="author-name">{{ $comment->full_name }}</h4>
                                                                <div class="comment-date">{{ $comment->persianCreatedAt('d F Y') }}</div>
                                                            </div>
{{--                                                            <div class="comment-reply">--}}
{{--                                                                <a href="#" class="reply"><span class="icons"><i class="ti-back-left"></i></span> پاسخ</a>--}}
{{--                                                            </div>--}}
                                                        </div>
                                                        <div class="comment-text">
                                                            <p>{{ $comment->comment }}</p>
                                                        </div>
                                                    </div>
                                                </article>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="comment-box submit-form">
                                <h3 class="reply-title">@lang('title.send_comment')</h3>
                                <div class="comment-form">
                                    <form action="{{ route('main.articles.comments.store', $article->id) }}" method="POST">
                                        @csrf

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <score-stars></score-stars>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <input value="{{ old('name', auth()->user()->full_name ?? '') }}"
                                                           name="name" type="text" class="form-control"
                                                           placeholder="@lang('title.your_name')">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <input value="{{ old('email', auth()->user()->email ?? '') }}"
                                                           name="email" type="email" class="form-control"
                                                           placeholder="@lang('title.your_email')">
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="form-group">
                                                <textarea name="comment" class="form-control" cols="30" rows="6"
                                                          placeholder="@lang('text.type_your_comment')"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    <button type="submit"
                                                            class="btn search-btn">@lang('title.send_comment')</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>

                <!-- Single blog Grid -->
                <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <x-blog.sidebar></x-blog.sidebar>
                </div>

            </div>
            <!-- /row -->

        </div>

    </section>
    <!-- ============================ Agency List End ================================== -->
@endsection
