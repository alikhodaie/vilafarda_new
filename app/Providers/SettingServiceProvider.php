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
            'zarinpal.gate' => settingBoolean('zarinpal:gate'),
            'zarinpal.sandbox' => settingBoolean('zarinpal:sandbox'),
            'idpay.api_key' => setting('idpay:api-key') ?: config('idpay.api_key'),
            'idpay.sandbox_status' => settingBoolean('idpay:sandbox') || config('idpay.sandbox_status'),
        ]);
    }
}
