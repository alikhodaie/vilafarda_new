<div class="search-container sticky-top bg-white" style="padding: 12px 16px !important; border-bottom: 1px solid #e0e0e0 !important; box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important; width: 100% !important; z-index: 100 !important;">
    <form class="search-form" action="{{ route('main.homes.index') }}" method="get" style="width: 100% !important; display: flex !important; align-items: center !important; gap: 10px !important; max-width: 100% !important;">
        <div class="search-input-wrapper" style="flex: 1 !important; position: relative !important; display: flex !important; align-items: center !important; background: #ffffff !important; border-radius: 10px !important; border: 2px solid #e0e0e0 !important; transition: all 0.3s ease !important; overflow: hidden !important; height: 40px !important; box-sizing: border-box !important; box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;">
            <input
                type="text"
                name="name"
                class="search-input"
                placeholder="@lang('text.homes_search_placeholder')"
                aria-label="جستجو"
                value="{{ request('name') }}"
                style="flex: 1 !important; border: none !important; background: transparent !important; padding: 0 14px !important; font-size: 14px !important; color: #333 !important; outline: none !important; font-family: inherit !important; height: 100% !important; line-height: 1.5 !important; box-sizing: border-box !important; width: 100% !important;"
            >
        </div>
        <button type="submit" class="search-button" style="background: #D39D1A !important; border: none !important; color: white !important; padding: 0 !important; border-radius: 10px !important; cursor: pointer !important; transition: all 0.3s ease !important; display: flex !important; align-items: center !important; justify-content: center !important; min-width: 40px !important; width: 40px !important; height: 40px !important; flex-shrink: 0 !important; box-sizing: border-box !important; box-shadow: 0 2px 4px rgba(211, 157, 26, 0.3) !important;">
            <i class="fas fa-search" style="font-size: 16px !important; line-height: 1 !important; color: white !important;"></i>
        </button>
    </form>
</div>

<style>
.search-container {
    z-index: 100 !important;
    width: 100% !important;
}

.search-form {
    width: 100% !important;
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    max-width: 100% !important;
}

.search-input-wrapper {
    flex: 1 !important;
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    background: #ffffff !important;
    border-radius: 10px !important;
    border: 2px solid #e0e0e0 !important;
    transition: all 0.3s ease !important;
    overflow: hidden !important;
    height: 40px !important;
    box-sizing: border-box !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
}

.search-input-wrapper:focus-within,
.search-input-wrapper:focus-within {
    border-color: #D39D1A !important;
    background: #ffffff !important;
    box-shadow: 0 2px 8px rgba(211, 157, 26, 0.2) !important;
}

.search-input:focus + .search-input-wrapper,
.search-input-wrapper:has(.search-input:focus) {
    border-color: #D39D1A !important;
    box-shadow: 0 2px 8px rgba(211, 157, 26, 0.2) !important;
}

.search-input {
    flex: 1 !important;
    border: none !important;
    background: transparent !important;
    padding: 0 14px !important;
    font-size: 14px !important;
    color: #333 !important;
    outline: none !important;
    font-family: inherit !important;
    height: 100% !important;
    line-height: 1.5 !important;
    box-sizing: border-box !important;
    width: 100% !important;
}

.search-input::placeholder {
    color: #999 !important;
    font-size: 14px !important;
}

.search-button {
    background: #D39D1A !important;
    border: none !important;
    color: white !important;
    padding: 0 !important;
    border-radius: 10px !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 40px !important;
    width: 40px !important;
    height: 40px !important;
    flex-shrink: 0 !important;
    box-sizing: border-box !important;
    box-shadow: 0 2px 4px rgba(211, 157, 26, 0.3) !important;
}

.search-button:hover {
    background: #B8860B !important;
    box-shadow: 0 3px 6px rgba(211, 157, 26, 0.4) !important;
    transform: translateY(-1px) !important;
}

.search-button:active {
    background: #996f0a !important;
    transform: translateY(0) !important;
    box-shadow: 0 1px 2px rgba(211, 157, 26, 0.3) !important;
}

.search-button i {
    font-size: 16px !important;
    line-height: 1 !important;
}

/* Mobile responsive improvements */
@media (max-width: 576px) {
    .search-container {
        padding: 10px 12px !important;
    }
    
    .search-form {
        gap: 8px !important;
    }
    
    .search-input-wrapper {
        height: 38px !important;
        border-radius: 8px !important;
        border-width: 1.5px !important;
    }
    
    .search-input {
        padding: 0 12px !important;
        font-size: 13px !important;
    }
    
    .search-input::placeholder {
        font-size: 12px !important;
    }
    
    .search-button {
        min-width: 38px !important;
        width: 38px !important;
        height: 38px !important;
        border-radius: 8px !important;
    }
    
    .search-button i {
        font-size: 14px !important;
    }
}
</style>
