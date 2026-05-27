<div class="homes-mobile-search-wrap">
    <form class="homes-mobile-search-form" action="{{ route('dashboard.favorites.index') }}" method="get" id="favoritesMobileSearchForm">
        <div class="homes-mobile-search-input-wrap">
            <input
                type="text"
                name="q"
                id="favoritesMobileSearchInput"
                class="homes-mobile-search-input"
                value="{{ request('q') }}"
                placeholder="@lang('text.favorites_search_placeholder')"
                aria-label="@lang('text.favorites_search_placeholder')"
                autocomplete="off"
            >
        </div>
        <button type="submit" class="homes-mobile-search-btn" aria-label="جستجو">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>
