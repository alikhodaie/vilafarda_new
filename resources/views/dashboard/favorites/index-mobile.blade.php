@extends('layouts.main.main_mobile', ['title' => __('title.favorites')])

@section('styles')
    @include('main.homes.partials.mobile-search-styles')
@endsection

@section('content')
    @include('layouts.main.partials.navbar-mobile')

    <div class="container px-3 py-3">
        <div class="page-header-card">
            <h1 class="page-header-title">@lang('title.favorites')</h1>
            <p class="page-header-subtitle">علاقه‌مندی‌های شما</p>
        </div>
    </div>

    <div class="container px-3 pb-2">
        <div class="favorites-remove-hint rounded-3 p-3 mb-2" style="background: #fff8e6; border: 1px solid #e8d9b8; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
            <p class="mb-0" style="font-size: 13px; line-height: 1.6; color: #5c4a1a;">
                <i class="bi bi-info-circle me-1" style="color: #D39D1A;"></i>
                @lang('text.favorites_remove_hint')
            </p>
        </div>
    </div>

    <div class="container px-3 pt-2 pb-2">
        @include('dashboard.favorites.partials.mobile-search-bar')
    </div>

    <div class="container px-3 pb-4" id="favoritesListContainer">
        @php
            $homeFavorites = $favorites->filter(fn ($f) => $f->favoritable_type === \App\Models\Home::class && $f->favoritable);
        @endphp

        @if($homeFavorites->isNotEmpty())
            <div id="favoritesCardsList">
                @foreach($homeFavorites as $favorite)
                    @include('main.homes.partials.mobile-home-card', ['home' => $favorite->favoritable])
                @endforeach
            </div>

            @if($favorites->hasPages())
                <div class="d-flex justify-content-center mt-4 mb-4">
                    <div class="pagination-wrapper" style="background: #f8f9fa; padding: 16px; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        {{ $favorites->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        @else
            <div id="favoritesEmptyState" class="bg-white rounded-3 p-4 text-center" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <i class="bi bi-heart fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">
                    @if(request('q'))
                        نتیجه‌ای برای «{{ request('q') }}» پیدا نشد
                    @else
                        @lang('title.nothing found')
                    @endif
                </h5>
                <p class="text-muted mb-3" style="font-size: 14px;">
                    @if(request('q'))
                        عبارت جستجو را تغییر دهید یا فیلتر را پاک کنید
                    @else
                        هنوز هیچ علاقه‌مندی‌ای اضافه نکرده‌اید
                    @endif
                </p>
                @if(request('q'))
                    <a href="{{ route('dashboard.favorites.index') }}" class="btn btn-outline-secondary me-2" style="font-size: 14px; border-radius: 12px;">
                        پاک کردن جستجو
                    </a>
                @endif
                <a href="{{ route('main.homes.index') }}" class="btn btn-primary" style="font-size: 14px; background: #D39D1A; border-color: #D39D1A; border-radius: 12px;">
                    <i class="bi bi-search me-2"></i>
                    مشاهده اقامتگاه‌ها
                </a>
            </div>
        @endif
    </div>

@endsection
