@extends('layouts.main.main_mobile', ['title' => __('title.comments')])

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="container px-3 py-3">
        <div class="bg-white rounded-3 p-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h1 class="fw-bold mb-2" style="font-size: 18px; color: #333;">@lang('title.comments')</h1>
            <p class="mb-0" style="font-size: 14px; color: #666;">نظرات اقامتگاه‌های شما</p>
        </div>
    </div>

    <!-- Comments List -->
    <div class="container px-3 py-4">
        @if($comments->isNotEmpty())
            @foreach($comments as $comment)
                <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <!-- Comment Header -->
                    <div class="d-flex align-items-start mb-3">
                        @if($comment->user)
                            <img src="{{ $comment->user->avatar_path }}" class="rounded-circle me-3" 
                                 alt="{{ $comment->full_name }}" style="width: 40px; height: 40px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px;">
                                <i class="bi bi-person text-white"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1" style="font-size: 14px; color: #333;">
                                {{ $comment->full_name }}
                            </h6>
                            <small class="text-muted" style="font-size: 12px;">
                                {{ $comment->persianCreatedAt('d F - H:i') }}
                            </small>
                        </div>
                    </div>

                    <!-- Home Info -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-house text-primary me-2" style="font-size: 14px;"></i>
                            <a href="{{ route('main.homes.show', $comment->commentable) }}" 
                               class="text-decoration-none" style="font-size: 14px; color: #D39D1A;">
                                {{ $comment->commentable->name }}
                            </a>
                        </div>
                    </div>

                    <!-- Comment Content -->
                    <div class="mb-3">
                        <p class="mb-0" style="font-size: 14px; line-height: 1.5; color: #555;">
                            {{ $comment->comment }}
                        </p>
                    </div>

                    <!-- Reply Section -->
                    @if($comment->children_count > 0)
                        <div class="mb-3">
                            <button class="btn btn-outline-primary btn-sm" type="button" 
                                    data-bs-toggle="collapse" data-bs-target="#replies-{{ $comment->id }}" 
                                    style="font-size: 12px;">
                                <i class="bi bi-chat-dots me-1"></i>
                                {{ $comment->children_count }} پاسخ
                            </button>
                            
                            <div class="collapse mt-2" id="replies-{{ $comment->id }}">
                                @foreach($comment->activeChildren as $reply)
                                    <div class="bg-light rounded p-2 mb-2">
                                        <div class="d-flex align-items-start">
                                            @if($reply->user)
                                                <img src="{{ $reply->user->avatar_path }}" class="rounded-circle me-2" 
                                                     alt="{{ $reply->full_name }}" style="width: 30px; height: 30px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 30px; height: 30px;">
                                                    <i class="bi bi-person text-white" style="font-size: 12px;"></i>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <h6 class="fw-bold mb-0" style="font-size: 12px; color: #333;">
                                                        {{ $reply->full_name }}
                                                    </h6>
                                                    <small class="text-muted" style="font-size: 10px;">
                                                        {{ $reply->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                                <p class="mb-0" style="font-size: 12px; color: #555;">
                                                    {{ $reply->comment }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Reply Form -->
                    <div class="border-top pt-3">
                        <form action="{{ route('dashboard.comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="reply_id" value="{{ $comment->id }}">
                            <div class="mb-2">
                                <textarea name="comment" class="form-control" rows="2" 
                                          placeholder="پاسخ خود را بنویسید..." style="font-size: 14px;"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm" style="background: #D39D1A; border-color: #D39D1A; font-size: 12px;">
                                <i class="bi bi-send me-1"></i>
                                ارسال پاسخ
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            @if($comments->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $comments->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-3 p-4 text-center" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <i class="bi bi-chat-dots fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">هنوز نظری دریافت نکرده‌اید</h5>
                <p class="text-muted mb-3" style="font-size: 14px;">
                    اقامتگاه‌های خود را ثبت کنید تا مهمانان بتوانند نظر بدهند
                </p>
                <a href="{{ route('dashboard.homes.create') }}" class="btn btn-primary" style="background: #D39D1A; border-color: #D39D1A;">
                    <i class="bi bi-plus-circle me-2"></i>
                    ثبت اقامتگاه جدید
                </a>
            </div>
        @endif
    </div>
@endsection
