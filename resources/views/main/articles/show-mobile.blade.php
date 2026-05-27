@extends('layouts.main.main_mobile', ['title' => $article->title])

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="hero-image" style="background: #f4f4f4 url({{ asset('assets/img/slider-2.jpg') }}); background-size: cover; background-position: center; height: 200px; position: relative;">
        <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center text-white px-3">
                <h1 class="fw-bold mb-2" style="font-size: 18px;">{{ $article->title }}</h1>
                <p class="mb-0" style="font-size: 14px; opacity: 0.9;">جزئیات وبلاگ</p>
            </div>
        </div>
    </div>

    <!-- Article Content -->
    <div class="container px-3 py-4">
        <div class="bg-white rounded-3 p-3 mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <!-- Article Image -->
            @if($article->image)
                <div class="mb-3">
                    <img src="{{ $article->image_path }}" alt="{{ $article->title }}" 
                         class="img-fluid rounded-2" style="width: 100%; height: 250px; object-fit: cover;">
                </div>
            @endif

            <!-- Article Meta -->
            <div class="d-flex flex-wrap gap-3 mb-3" style="font-size: 12px; color: #666;">
                @if($article->author)
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person me-1"></i>
                        <span>{{ $article->author->full_name }}</span>
                    </div>
                @endif
                <div class="d-flex align-items-center">
                    <i class="bi bi-calendar me-1"></i>
                    <span>{{ $article->created_at->format('Y/m/d') }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-eye me-1"></i>
                    <span>{{ $article->view_count ?? 0 }} بازدید</span>
                </div>
            </div>

            <!-- Article Content -->
            <div class="article-content" style="font-size: 14px; line-height: 1.8; color: #333;">
                {!! $article->content !!}
            </div>

            <!-- Tags -->
            @if($article->tags->count() > 0)
                <div class="mt-4 pt-3" style="border-top: 1px solid #eee;">
                    <h6 class="fw-bold mb-2" style="font-size: 14px;">برچسب‌ها:</h6>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($article->tags as $tag)
                            <span class="badge bg-light text-primary rounded-pill" style="font-size: 11px;">
                                {{ $tag->title }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Comments Section -->
        @if($article->activeComments->count() > 0)
            <div class="bg-white rounded-3 p-3 mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h5 class="fw-bold mb-3" style="font-size: 16px;">نظرات ({{ $article->activeComments->count() }})</h5>
                
                @foreach($article->activeComments as $comment)
                    <div class="comment-item mb-3 pb-3" style="border-bottom: 1px solid #f0f0f0;">
                        <div class="d-flex gap-3">
                            @if($comment->user)
                                <img src="{{ $comment->user->avatar_path }}" 
                                     alt="{{ $comment->full_name }}"
                                     class="rounded-circle"
                                     width="40" height="40">
                            @endif
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0" style="font-size: 14px;">{{ $comment->full_name }}</h6>
                                    <small class="text-muted" style="font-size: 11px;">
                                        {{ $comment->created_at->format('Y/m/d H:i') }}
                                    </small>
                                </div>
                                <p class="mb-0" style="font-size: 13px; color: #666; line-height: 1.6;">{{ $comment->comment }}</p>
                            </div>
                        </div>

                        <!-- Reply Comments -->
                        @foreach($comment->activeChildren as $child)
                            <div class="comment-item ms-4 mt-3">
                                <div class="d-flex gap-3">
                                    @if($child->user)
                                        <img src="{{ $child->user->avatar_path }}" 
                                             alt="{{ $child->full_name }}"
                                             class="rounded-circle"
                                             width="35" height="35">
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold mb-0" style="font-size: 13px;">{{ $child->full_name }}</h6>
                                            <small class="text-muted" style="font-size: 10px;">
                                                {{ $child->created_at->format('Y/m/d H:i') }}
                                            </small>
                                        </div>
                                        <p class="mb-0" style="font-size: 12px; color: #666; line-height: 1.6;">{{ $child->comment }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Add Comment Form -->
        @auth
            <div class="bg-white rounded-3 p-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h5 class="fw-bold mb-3" style="font-size: 16px;">ثبت نظر</h5>
                
                <form action="{{ route('main.articles.comments.store', $article->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label" style="font-size: 14px;">نام شما</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', auth()->user()->full_name) }}" 
                               style="font-size: 14px;">
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label" style="font-size: 14px;">ایمیل</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ old('email', auth()->user()->email) }}" 
                               style="font-size: 14px;">
                    </div>
                    
                    <div class="mb-3">
                        <label for="comment" class="form-label" style="font-size: 14px;">نظر شما</label>
                        <textarea class="form-control" id="comment" name="comment" rows="4" 
                                  style="font-size: 14px;">{{ old('comment') }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100" style="font-size: 14px; background: #D39D1A; border-color: #D39D1A;">
                        <i class="bi bi-send me-2"></i>
                        ارسال نظر
                    </button>
                </form>
            </div>
        @else
            <div class="bg-light rounded-3 p-3 text-center">
                <p class="text-muted mb-0" style="font-size: 14px;">
                    برای ثبت نظر باید وارد حساب خود شوید
                </p>
                <a href="{{ route('main.login') }}" class="btn btn-primary btn-sm mt-2" style="font-size: 12px; background: #D39D1A; border-color: #D39D1A;">
                    ورود / ثبت‌نام
                </a>
            </div>
        @endauth
    </div>
@endsection

