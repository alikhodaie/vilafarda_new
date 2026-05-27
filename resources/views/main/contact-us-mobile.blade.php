@extends('layouts.main.main_mobile', ['title' => setting('contact-us:title')])

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="hero-image" style="background: #f4f4f4 url({{ settingFilePath('contact-us:banner') }}); background-size: cover; background-position: center; height: 200px; position: relative;">
        <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center text-white px-3">
                <h1 class="fw-bold mb-2" style="font-size: 20px;">{{ setting('contact-us:title') }}</h1>
                <p class="mb-0" style="font-size: 14px; opacity: 0.9;">{{ setting('contact-us:description1') }}</p>
            </div>
        </div>
    </div>

    <!-- Contact Boxes -->
    <div class="container px-3 py-4">
        <div class="row">
            @if(setting('contact-us:box1-title') || setting('contact-us:box1-email') || setting('contact-us:box1-phone'))
                <div class="col-12 mb-3">
                    <div class="bg-white rounded-3 p-3 text-center" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <i class="bi bi-cart3 fs-1 mb-2" style="color: #007bff;"></i>
                        <h4 class="fw-bold mb-2" style="font-size: 16px;">{{ setting('contact-us:box1-title') }}</h4>
                        <p class="mb-1" style="font-size: 14px;">
                            <a href="mailto:{{ setting('contact-us:box1-email') }}" class="text-decoration-none text-primary">
                                {{ setting('contact-us:box1-email') }}
                            </a>
                        </p>
                        <span style="font-size: 14px;">
                            <a href="tel:{{ setting('contact-us:box1-phone') }}" class="text-decoration-none text-dark">
                                {{ setting('contact-us:box1-phone') }}
                            </a>
                        </span>
                    </div>
                </div>
            @endif

            @if(setting('contact-us:box2-title') || setting('contact-us:box2-email') || setting('contact-us:box2-phone'))
                <div class="col-12 mb-3">
                    <div class="bg-white rounded-3 p-3 text-center" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <i class="bi bi-person fs-1 mb-2" style="color: #28a745;"></i>
                        <h4 class="fw-bold mb-2" style="font-size: 16px;">{{ setting('contact-us:box2-title') }}</h4>
                        <p class="mb-1" style="font-size: 14px;">
                            <a href="mailto:{{ setting('contact-us:box2-email') }}" class="text-decoration-none text-primary">
                                {{ setting('contact-us:box2-email') }}
                            </a>
                        </p>
                        <span style="font-size: 14px;">
                            <a href="tel:{{ setting('contact-us:box2-phone') }}" class="text-decoration-none text-dark">
                                {{ setting('contact-us:box2-phone') }}
                            </a>
                        </span>
                    </div>
                </div>
            @endif

            @if(setting('contact-us:box3-title') || setting('contact-us:box3-email') || setting('contact-us:box3-phone'))
                <div class="col-12 mb-3">
                    <div class="bg-white rounded-3 p-3 text-center" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <i class="bi bi-chat-dots fs-1 mb-2" style="color: #ffc107;"></i>
                        <h4 class="fw-bold mb-2" style="font-size: 16px;">{{ setting('contact-us:box3-title') }}</h4>
                        <p class="mb-1" style="font-size: 14px;">
                            <a href="mailto:{{ setting('contact-us:box3-email') }}" class="text-decoration-none text-primary">
                                {{ setting('contact-us:box3-email') }}
                            </a>
                        </p>
                        <span style="font-size: 14px;">
                            <a href="tel:{{ setting('contact-us:box3-phone') }}" class="text-decoration-none text-dark">
                                {{ setting('contact-us:box3-phone') }}
                            </a>
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Contact Form -->
    <div class="container px-3 pb-4">
        <div class="bg-white rounded-3 p-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h4 class="fw-bold mb-3" style="font-size: 16px;">@lang('title.fill_form')</h4>
            
            <form method="POST" action="{{ route('main.contact.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label" style="font-size: 14px;">@lang('title.name')</label>
                    <input id="name" name="name" value="{{ old('name') }}" type="text" class="form-control" style="font-size: 14px;">
                </div>
                
                <div class="mb-3">
                    <label for="mobile" class="form-label" style="font-size: 14px;">@lang('title.mobile')</label>
                    <input id="mobile" name="mobile" value="{{ old('mobile') }}" type="text" class="form-control" style="font-size: 14px;">
                </div>
                
                <div class="mb-3">
                    <label for="subject" class="form-label" style="font-size: 14px;">@lang('title.subject')</label>
                    <input id="subject" name="subject" value="{{ old('subject') }}" type="text" class="form-control" style="font-size: 14px;">
                </div>
                
                <div class="mb-3">
                    <label for="message" class="form-label" style="font-size: 14px;">@lang('title.message')</label>
                    <textarea id="message" name="message" class="form-control" rows="4" style="font-size: 14px;">{!! old('message') !!}</textarea>
                </div>
                
                <button class="btn btn-primary w-100" type="submit" style="font-size: 14px; background: #D39D1A; border-color: #D39D1A;">@lang('title.send_request')</button>
            </form>
        </div>
    </div>

    <!-- Map Section -->
    @if(setting('contact-us:map-iframe'))
        <div class="container px-3 pb-4">
            <div class="bg-white rounded-3 p-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h4 class="fw-bold mb-3" style="font-size: 16px;">موقعیت ما</h4>
                <div class="ratio" style="--bs-aspect-ratio: 56.25%;">
                    {!! setting('contact-us:map-iframe') !!}
                </div>
            </div>
        </div>
    @endif

    @if($articles->isNotEmpty())
    <!-- Articles Section -->
    <div class="container px-3 pb-4">
        @if(setting('contact-us:article-title') || setting('contact-us:article-description'))
            <div class="text-center mb-3">
                <h3 class="fw-bold mb-2" style="font-size: 16px;">{{ setting('contact-us:article-title') }}</h3>
                <p class="text-muted mb-0" style="font-size: 14px;">{!! setting('contact-us:article-description') !!}</p>
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

