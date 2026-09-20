<?php

namespace App\Providers;

use App\Http\View\Composers\InboxUnreadComposer;
use App\Http\View\Composers\SeoComposer;
use App\Services\UserInboxService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserInboxService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        Blade::directive('selected', function ($expression) {
            return "<?php if ({$expression}): echo 'selected'; endif; ?>";
        });

        Blade::directive('checked', function ($expression) {
            return "<?php if ({$expression}): echo 'checked'; endif; ?>";
        });

        View::composer([
            'layouts.main.main',
            'layouts.main.main_mobile',
            'layouts.dashboard.dashboard',
            'layouts.dashboard.dashboard-mobile',
        ], SeoComposer::class);

        View::composer([
            'components.bottom-bar',
            'layouts.dashboard.partials.sidebar-items',
            'layouts.dashboard.partials.sidebar-items-mobile',
        ], InboxUnreadComposer::class);
    }
}
