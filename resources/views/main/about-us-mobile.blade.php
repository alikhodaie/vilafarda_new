@extends('layouts.main.main_mobile', ['title' => setting('about-us:page-title')])

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="hero-image" style="background: #f4f4f4 url({{ settingFilePath('about-us:banner') }}); background-size: cover; background-position: center; height: 200px; position: relative;">
        <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center text-white px-3">
                <h1 class="fw-bold mb-2" style="font-size: 20px;">{{ setting('about-us:title') }}</h1>
                <p class="mb-0" style="font-size: 14px; opacity: 0.9;">{{ setting('about-us:page-title') }}</p>
            </div>
        </div>
    </div>

    <!-- Our Story Section -->
    <div class="container px-3 py-4">
        <div class="bg-white rounded-3 p-3 mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 class="fw-bold mb-2" style="font-size: 18px;">{{ setting('about-us:story-title') }}</h2>
            <span class="text-primary fw-bold" style="font-size: 14px;">{{ setting('about-us:story-title1') }}</span>
            <p class="mt-3 mb-3" style="font-size: 14px; line-height: 1.6; color: #666;">
                {!! setting('about-us:story-description') !!}
            </p>
            @if(setting('about-us:story-btn-title'))
                <a href="{{ setting('about-us:story-btn-link') }}" class="btn btn-primary btn-sm" style="font-size: 14px; background: #D39D1A; border-color: #D39D1A;">
                    {{ setting('about-us:story-btn-title') }}
                </a>
            @endif
        </div>

        @if(setting('about-us:story-image'))
            <div class="text-center mb-4">
                <img src="{{ settingFilePath('about-us:story-image') }}" class="img-fluid rounded-3" alt="داستان ما" style="max-height: 300px; object-fit: cover;">
            </div>
        @endif
    </div>

    <!-- Counter Section -->
    <div class="container px-3 pb-4">
        <div class="bg-warning rounded-3 p-4 text-center" style="background: linear-gradient(135deg, #D39D1A 0%, #B8860B 100%);">
            <h2 class="text-white fw-bold mb-3" style="font-size: 18px;">{{ setting('about-us:reward-title') }}</h2>
            <p class="text-white mb-4" style="font-size: 14px; opacity: 0.9;">{{ setting('about-us:reward-description') }}</p>
            
            <div class="row">
                <div class="col-6 mb-3">
                    <div class="text-center">
                        <i class="bi bi-trophy-fill text-white mb-2" style="font-size: 24px;"></i>
                        <h5 class="text-white fw-bold mb-1" style="font-size: 16px;">{{ setting('about-us:reward-box1-count') }}</h5>
                        <span class="text-white" style="font-size: 12px;">{{ setting('about-us:reward-box1-title') }}</span>
                    </div>
                </div>
                <div class="col-6 mb-3">
                    <div class="text-center">
                        <i class="bi bi-briefcase-fill text-white mb-2" style="font-size: 24px;"></i>
                        <h5 class="text-white fw-bold mb-1" style="font-size: 16px;">{{ setting('about-us:reward-box2-count') }}</h5>
                        <span class="text-white" style="font-size: 12px;">{{ setting('about-us:reward-box2-title') }}</span>
                    </div>
                </div>
                <div class="col-6 mb-3">
                    <div class="text-center">
                        <i class="bi bi-lightbulb-fill text-white mb-2" style="font-size: 24px;"></i>
                        <h5 class="text-white fw-bold mb-1" style="font-size: 16px;">{{ setting('about-us:reward-box3-count') }}</h5>
                        <span class="text-white" style="font-size: 12px;">{{ setting('about-us:reward-box3-title') }}</span>
                    </div>
                </div>
                <div class="col-6 mb-3">
                    <div class="text-center">
                        <i class="bi bi-heart-fill text-white mb-2" style="font-size: 24px;"></i>
                        <h5 class="text-white fw-bold mb-1" style="font-size: 16px;">{{ setting('about-us:reward-box4-count') }}</h5>
                        <span class="text-white" style="font-size: 12px;">{{ setting('about-us:reward-box4-title') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php($comments = json_decode(setting('about-us:comments'), true) ?: [])
    @if(! empty($comments))
    <!-- Testimonials Section -->
    <div class="container px-3 pb-4">
        <div class="text-center mb-3">
            <h2 class="fw-bold mb-2" style="font-size: 18px;">{{ setting('about-us:comments-title') }}</h2>
            <p class="text-muted mb-0" style="font-size: 14px;">{{ setting('about-us:comments-description') }}</p>
        </div>

        <div class="row">
            @foreach($comments as $comment)
                <div class="col-12 mb-3">
                    <div class="bg-white rounded-3 p-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="fw-bold mb-1" style="font-size: 16px;">{{ $comment['name'] }}</h5>
                                <span class="text-muted" style="font-size: 12px;">{{ $comment['job'] }}</span>
                            </div>
                            <div class="text-warning">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="bi bi-star-fill" style="font-size: 12px;"></i>
                                @endfor
                                <span class="text-muted ms-1" style="font-size: 12px;">{{ $comment['score'] }}</span>
                            </div>
                        </div>
                        <p class="mb-0" style="font-size: 14px; color: #666; line-height: 1.6;">{{ $comment['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Articles Section -->
    <div class="container px-3 pb-4">
        <div class="text-center mb-3">
            <h2 class="fw-bold mb-2" style="font-size: 18px;">{{ setting('about-us:articles-title') }}</h2>
            <p class="text-muted mb-0" style="font-size: 14px;">{{ setting('about-us:articles-description') }}</p>
        </div>

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
@endsection

