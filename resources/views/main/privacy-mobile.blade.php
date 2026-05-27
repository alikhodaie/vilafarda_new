@extends('layouts.main.main_mobile', ['title' => setting('privacy:title')])

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="hero-image" style="background: #f4f4f4 url({{ settingFilePath('privacy:banner') }}); background-size: cover; background-position: center; height: 200px; position: relative;">
        <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center text-white px-3">
                <h1 class="fw-bold mb-2" style="font-size: 20px;">{{ setting('privacy:title') }}</h1>
                <p class="mb-0" style="font-size: 14px; opacity: 0.9;">{{ setting('privacy:description1') }}</p>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="container px-3 py-4">
        <div class="bg-white rounded-3 p-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            {!! setting('privacy:content') !!}
        </div>
    </div>

    @if($articles->isNotEmpty())
    <!-- Articles Section -->
    <div class="container px-3 pb-4">
        @if(setting('privacy:article-title') || setting('privacy:article-description'))
            <div class="text-center mb-3">
                <h3 class="fw-bold mb-2" style="font-size: 16px;">{{ setting('privacy:article-title') }}</h3>
                <p class="text-muted mb-0" style="font-size: 14px;">{!! setting('privacy:article-description') !!}</p>
            </div>
        @endif

        @foreach($articles as $article)
            <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h5 class="fw-bold mb-2" style="font-size: 16px;">
                    <a href="{{ route('main.articles.show', ['id' => $article->id, 'slug' => $article->slug]) }}" 
                       class="text-decoration-none text-dark">
                        {{ $article->title }}
                    </a>
                </h5>
                <p class="text-muted mb-2" style="font-size: 14px;">
                    {{ Str::limit(strip_tags($article->content), 100) }}
                </p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted" style="font-size: 12px;">
                        {{ $article->author->name ?? 'نویسنده' }}
                    </small>
                    <small class="text-muted" style="font-size: 12px;">
                        {{ $article->created_at->format('Y/m/d') }}
                    </small>
                </div>
            </div>
        @endforeach
    </div>
    @endif
@endsection
