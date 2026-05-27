<!-- Floating Dark Bottom Bar -->
<div class="d-md-none position-fixed start-0 end-0 mb-3 px-4" style="bottom: 0; z-index: 1055;">
    <div class="px-3 py-2 d-flex justify-content-between align-items-center"
         style="border-radius:1rem; background-color: rgba(17, 17, 17, 0.89); backdrop-filter:blur(10px); box-shadow:0 4px 12px rgba(0,0,0,0.1);">

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
                    <i class="bi {{ Route::is('main.login') ? 'bi-box-arrow-in-right' : 'bi-box-arrow-in-right' }} fa-lg"></i>
                </div>
                <div style="font-size: 0.7rem;">ورود</div>
            </a>
        @else
            <a href="{{ route('dashboard.index') }}" class="text-center text-decoration-none flex-fill {{ Route::is('dashboard.index') ? 'text-warning' : 'text-white' }}">
                <div class="mb-1">
                    <i class="bi {{ Route::is('dashboard.index') ? 'bi-person-fill' : 'bi-person' }} fa-lg"></i>
                </div>
                <div style="font-size: 0.7rem;">پروفایل</div>
            </a>
        @endguest

    </div>
</div>

