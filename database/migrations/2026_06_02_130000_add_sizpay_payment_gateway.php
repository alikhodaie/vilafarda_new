<?php

use App\Models\Setting;
use App\Models\Transaction;
use App\Services\PaymentGatewayService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('transactions')) {
            DB::statement("ALTER TABLE `transactions` MODIFY `gateway` VARCHAR(32) NOT NULL");
        }

        if (! Schema::hasTable('settings')) {
            return;
        }

        $defaults = [
            PaymentGatewayService::settingKey(Transaction::SIZPAY) => '0',
            'sizpay:merchant-id' => '',
            'sizpay:terminal-id' => '',
            'sizpay:username' => '',
            'sizpay:password' => '',
            'sizpay:sign-data' => '',
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
            PaymentGatewayService::settingKey(Transaction::SIZPAY),
            'sizpay:merchant-id',
            'sizpay:terminal-id',
            'sizpay:username',
            'sizpay:password',
            'sizpay:sign-data',
        ])->delete();
    }
};
