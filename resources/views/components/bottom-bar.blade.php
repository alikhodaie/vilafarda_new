<style>
    .mobile-bottom-nav {
        position: fixed !important;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1055;
        padding: 0 1rem calc(0.75rem + env(safe-area-inset-bottom, 0px));
    }
    .mobile-bottom-nav__bar {
        border-radius: 1rem;
        background-color: rgba(17, 17, 17, 0.89);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .mobile-bottom-nav-profile {
        position: relative;
    }
    .inbox-speech-bubble {
        position: absolute;
        bottom: calc(100% + 18px);
        left: 50%;
        z-index: 1060;
        padding: 7px 12px 8px;
        background: #fff;
        color: #1a1a1a;
        border: 1.5px solid #111;
        border-radius: 14px;
        font-size: 13px !important;
        font-weight: 800;
        line-height: 1.3;
        white-space: nowrap;
        text-decoration: none;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.18);
        transform: translateX(-30%);
        animation: inbox-speech-pop 5s ease-in-out infinite;
    }
    .inbox-speech-bubble::after {
        content: "";
        position: absolute;
        bottom: -6px;
        left: 22px;
        width: 10px;
        height: 10px;
        background: #fff;
        border-right: 1.5px solid #111;
        border-bottom: 1.5px solid #111;
        transform: rotate(45deg);
    }
    @keyframes inbox-speech-pop {
        0%, 14% { opacity: 0; }
        22%, 60% { opacity: 1; }
        72%, 100% { opacity: 0; }
    }
</style>
<!-- Floating Dark Bottom Bar -->
<div class="d-md-none mobile-bottom-nav">
    <div class="mobile-bottom-nav__bar px-3 py-2 d-flex justify-content-between align-items-center">
        <a href="/" class="text-center text-decoration-none flex-fill {{ request()->is('/') ? 'text-warning' : 'text-white' }}">
            <div class="mb-1">
                <i class="bi {{ request()->is('/') ? 'bi-house-fill' : 'bi-house' }} fa-lg"></i>
            </div>
            <div style="font-size: 0.7rem;">خانه</div>
        </a>

        <a href="{{ route('dashboard.rents.index') }}" class="text-center text-decoration-none flex-fill {{ Route::is('dashboard.rents.*') ? 'text-warning' : 'text-white' }}">
            <div class="mb-1">
                <i class="bi {{ Route::is('dashboard.rents.*') ? 'bi-suitcase2-fill' : 'bi-suitcase2' }} fa-lg"></i>
            </div>
            <div style="font-size: 0.7rem;">سفرهای من</div>
        </a>

        <a href="/homes" class="text-center text-decoration-none flex-fill {{ request()->is('homes') ? 'text-warning' : 'text-white' }}">
            <div class="mb-1">
                <i class="bi {{ request()->is('homes') ? 'bi-search-heart-fill' : 'bi-search' }} fa-lg"></i>
            </div>
            <div style="font-size: 0.7rem;">جستجو</div>
        </a>

        <a href="/dashboard/favorites" class="text-center text-decoration-none flex-fill {{ request()->is('dashboard/favorites') ? 'text-warning' : 'text-white' }}">
            <div class="mb-1">
                <i class="bi {{ request()->is('dashboard/favorites') ? 'bi-heart-fill' : 'bi-heart' }} fa-lg"></i>
            </div>
            <div style="font-size: 0.7rem;">علاقه مندی ها</div>
        </a>

        @guest
            <a href="{{ route('main.login') }}" class="text-center text-decoration-none flex-fill {{ Route::is('main.login') ? 'text-warning' : 'text-white' }}">
                <div class="mb-1">
                    <i class="bi bi-box-arrow-in-right fa-lg"></i>
                </div>
                <div style="font-size: 0.7rem;">ورود</div>
            </a>
        @else
            <div class="mobile-bottom-nav-profile text-center flex-fill {{ Route::is('dashboard.index') ? 'text-warning' : 'text-white' }}">
                @if(($inboxUnreadCount ?? 0) > 0)
                    <a href="{{ route('dashboard.inbox.index') }}" class="inbox-speech-bubble">@lang('title.inbox_you_have_a_message')</a>
                @endif
                <a href="{{ route('dashboard.index') }}" class="text-center text-decoration-none d-block {{ Route::is('dashboard.index') ? 'text-warning' : 'text-white' }}">
                    <div class="mb-1">
                        <i class="bi {{ Route::is('dashboard.index') ? 'bi-person-fill' : 'bi-person' }} fa-lg"></i>
                    </div>
                    <div style="font-size: 0.7rem;">پروفایل</div>
                </a>
            </div>
        @endguest
    </div>
</div>
