<?php

namespace App\Providers;

use App\Http\View\Composers\SeoComposer;
use Illuminate\Pagination\Paginator;
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

        View::composer([
            'layouts.main.main',
            'layouts.main.main_mobile',
            'layouts.dashboard.dashboard',
            'layouts.dashboard.dashboard-mobile',
        ], SeoComposer::class);
    }
}
