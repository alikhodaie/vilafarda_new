@extends('layouts.main.main_mobile', ['title' => setting('faq:title')])

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="hero-image" style="background: #f4f4f4 url({{ settingFilePath('faq:banner') }}); background-size: cover; background-position: center; height: 200px; position: relative;">
        <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center text-white px-3">
                <h1 class="fw-bold mb-3" style="font-size: 20px;">{{ setting('faq:title') }}</h1>
                
                <!-- Search Button -->
                <div class="mt-3">
                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#searchModal" style="font-size: 14px; background: #D39D1A; border-color: #D39D1A; color: white; border-radius: 12px;">
                        <i class="bi bi-search me-2"></i>
                        جستجو در سوالات متداول
                    </button>
                </div>

                <!-- Search Modal -->
                <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="searchModalLabel" style="font-size: 16px;">جستجو در سوالات متداول</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="GET" action="{{ route('main.faq') }}">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="search" class="form-label" style="font-size: 14px;">کلمه کلیدی</label>
                                        <input name="search" id="search" class="form-control" placeholder="@lang('title.search') ..." value="{{ request('search') }}" style="font-size: 14px;">
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
            </div>
        </div>
    </div>

    <!-- FAQ Content -->
    <div class="container px-3 py-4">
        @foreach($categories as $category)
            @if($category->faq->isNotEmpty())
                <div class="mb-4">
                    <h4 class="fw-bold mb-3" style="font-size: 16px; color: #333;">{{ $category->title }}</h4>
                    
                    <div class="accordion" id="mobile-{{ $category->name }}-{{ $category->id }}">
                        @foreach($category->faq as $faq)
                            <div class="card mb-2" style="border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <div class="card-header bg-white" style="border: none; padding: 0;">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link w-100 text-start p-3 @if(!$loop->first) collapsed @endif" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#mobile-collapse-{{ $faq->id }}" 
                                                aria-controls="mobile-collapse-{{ $faq->id }}"
                                                style="text-decoration: none; color: #333; font-size: 14px; white-space: normal; border: none; background: none;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-start">{{ $faq->question }}</span>
                                                <i class="bi bi-chevron-down" style="font-size: 12px; transition: transform 0.3s ease;"></i>
                                            </div>
                                        </button>
                                    </h2>
                                </div>

                                <div id="mobile-collapse-{{ $faq->id }}" 
                                     class="collapse @if($loop->first) show @endif" 
                                     aria-labelledby="heading-{{ $faq->id }}" 
                                     data-bs-parent="#mobile-{{ $category->name }}-{{ $category->id }}">
                                    <div class="card-body p-3 pt-0" style="font-size: 14px; color: #666; line-height: 1.6;">
                                        {{ $faq->answer }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection

@section('scripts')
<script>
    // Add smooth animation for chevron icons
    document.addEventListener('DOMContentLoaded', function() {
        const collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');
        
        collapseElements.forEach(function(element) {
            element.addEventListener('click', function() {
                const target = document.querySelector(element.getAttribute('data-bs-target'));
                const chevron = element.querySelector('.bi-chevron-down');
                
                if (target.classList.contains('show')) {
                    chevron.style.transform = 'rotate(0deg)';
                } else {
                    chevron.style.transform = 'rotate(180deg)';
                }
            });
        });
    });
</script>
@endsection

