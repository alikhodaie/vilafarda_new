<?php

use App\Models\Setting;
use App\Models\Transaction;
use App\Services\PaymentGatewayService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $defaults = [
            PaymentGatewayService::settingKey(Transaction::ZARINPAL) => '1',
            PaymentGatewayService::settingKey(Transaction::IDPAY) => '0',
            PaymentGatewayService::settingKey(Transaction::WALLET) => '0',
            'idpay:api-key' => '',
            'idpay:sandbox' => '0',
        ];

        foreach ($defaults as $key => $value) {
            Setting::query()->firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        Setting::query()->whereIn('key', [
            PaymentGatewayService::settingKey(Transaction::ZARINPAL),
            PaymentGatewayService::settingKey(Transaction::IDPAY),
            PaymentGatewayService::settingKey(Transaction::WALLET),
            'idpay:api-key',
            'idpay:sandbox',
        ])->delete();
    }
};
