<?php

namespace App\Providers;

use App\Http\View\Composers\SeoComposer;
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
        //
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
    }
}
