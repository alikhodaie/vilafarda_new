@php
    $onBanner = $onBanner ?? false;
@endphp

<div class="index-search-bar @if($onBanner) index-search-bar--on-banner @endif">
    <div class="@if($onBanner) container @endif">
        <div class="index-search-bar__inner">
            <form class="search-form" action="{{ route('main.homes.index') }}" method="get" role="search">
                <div class="search-input-wrapper">
                    <input
                        type="search"
                        name="name"
                        class="search-input"
                        placeholder="@lang('text.homes_search_placeholder')"
                        aria-label="@lang('title.search')"
                        value="{{ request('name') }}"
                        autocomplete="off"
                    >
                </div>
                <button type="submit" class="search-button" aria-label="@lang('title.search_home')">
                    <i class="fas fa-search" aria-hidden="true"></i>
                </button>
            </form>
        </div>
    </div>
</div>
