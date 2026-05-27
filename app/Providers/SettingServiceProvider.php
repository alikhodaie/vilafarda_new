<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        config([
            'zarinpal.merchant_id' => setting('zarinpal:merchant-id'),
            'zarinpal.gate' => setting('zarinpal:gate', false),
            'zarinpal.sandbox' => setting('zarinpal:sandbox', false),
        ]);
    }
}
