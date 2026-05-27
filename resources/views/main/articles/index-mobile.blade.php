@extends('layouts.main.main_mobile', ['title' => __('title.blog')])

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="hero-image" style="background: #f4f4f4 url({{ asset('assets/img/slider-3.jpg') }}); background-size: cover; background-position: center; height: 200px; position: relative;">
        <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center text-white px-3">
                <h1 class="fw-bold mb-2" style="font-size: 20px;">@lang('title.blog')</h1>
                <p class="mb-0" style="font-size: 14px; opacity: 0.9;">وبلاگ شبکه ای - وبلاگ های ما</p>
            </div>
        </div>
    </div>

    <!-- Search Button -->
    <div class="container px-3 py-4">
        <div class="bg-white rounded-3 p-3 mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#searchModal" style="font-size: 14px; background: #D39D1A; border-color: #D39D1A; color: white; border-radius: 12px;">
                <i class="bi bi-search me-2"></i>
                جستجو در مقالات
            </button>
        </div>
    </div>

    <!-- Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel" style="font-size: 16px;">جستجو در مقالات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ route('main.articles.index') }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="search" class="form-label" style="font-size: 14px;">کلمه کلیدی</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="کلمه کلیدی را وارد کنید..." value="{{ request('search') }}" 
                                   style="font-size: 14px;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 14px; border-radius: 12px;">انصراف</button>
                        <button type="submit" class="btn btn-primary" style="background: #D39D1A; border-color: #D39D1A; color: white; font-size: 14px; border-radius: 12px;">
                            <i class="bi bi-search me-2"></i>
                            جستجو
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Articles List -->
    <div class="container px-3 pb-4">
        @if($articles->count() > 0)
            @foreach($articles as $article)
                <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <!-- Article Image -->
                    @if($article->image)
                        <div class="mb-3">
                            <img src="{{ $article->image_path }}" alt="{{ $article->title }}" 
                                 class="img-fluid rounded-2" style="width: 100%; height: 200px; object-fit: cover;">
                        </div>
                    @endif

                    <!-- Article Content -->
                    <h5 class="fw-bold mb-2" style="font-size: 16px;">
                        <a href="{{ route('main.articles.show', ['id' => $article->id, 'slug' => $article->slug]) }}" 
                           class="text-decoration-none text-dark">
                            {{ $article->title }}
                        </a>
                    </h5>

                    <p class="text-muted mb-3" style="font-size: 14px; line-height: 1.6;">
                        {{ Str::limit(strip_tags($article->content), 120) }}
                    </p>

                    <!-- Article Meta -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            @if($article->author)
                                <img src="{{ $article->author->avatar_path }}" 
                                     alt="{{ $article->author->name }}" 
                                     class="rounded-circle me-2" 
                                     width="30" height="30">
                                <small class="text-muted" style="font-size: 12px;">
                                    {{ $article->author->name }}
                                </small>
                            @endif
                        </div>
                        <small class="text-muted" style="font-size: 12px;">
                            {{ $article->created_at->format('Y/m/d') }}
                        </small>
                    </div>

                    <!-- Categories -->
                    @if($article->categories->count() > 0)
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($article->categories as $category)
                                <span class="badge bg-light text-primary rounded-pill" style="font-size: 11px;">
                                    {{ $category->title }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $articles->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-file-text fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">مقاله‌ای یافت نشد</h5>
                <p class="text-muted" style="font-size: 14px;">
                    @if(request('search'))
                        نتیجه‌ای برای "{{ request('search') }}" پیدا نشد
                    @else
                        هنوز مقاله‌ای منتشر نشده است
                    @endif
                </p>
            </div>
        @endif
    </div>
@endsection

