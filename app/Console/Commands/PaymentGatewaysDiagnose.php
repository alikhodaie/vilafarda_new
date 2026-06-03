<?php

namespace App\Console\Commands;

use App\Classes\Payment\SizpayClient;
use App\Models\Transaction;
use App\Services\PaymentGatewayService;
use Illuminate\Console\Command;

class PaymentGatewaysDiagnose extends Command
{
    protected $signature = 'payment:diagnose';

    protected $description = 'وضعیت درگاه‌های پرداخت و پیش‌نیازهای سیزپی';

    public function handle(PaymentGatewayService $gateways): int
    {
        $this->info('=== درگاه‌های پرداخت ===');

        foreach (Transaction::GATEWAY as $key => $meta) {
            $enabled = $gateways->isEnabled($key) ? 'فعال' : 'غیرفعال';
            $configured = $gateways->isConfigured($key) ? 'پیکربندی شده' : 'ناقص';
            $checkout = ($gateways->isEnabled($key) && $gateways->isConfigured($key)) ? 'در صفحه پرداخت نمایش داده می‌شود' : '—';

            $this->line(sprintf('[%s] %s — %s — %s — %s', $key, $meta['text'], $enabled, $configured, $checkout));
        }

        $this->newLine();
        $this->info('=== سیزپی (جزئیات) ===');
        $this->line('SOAP: '.(SizpayClient::isExtensionAvailable() ? 'فعال' : 'غیرفعال'));
        $client = SizpayClient::fromSettings();
        $creds = $client->credentials();
        foreach (['merchant_id', 'terminal_id', 'username', 'password', 'sign_data'] as $field) {
            $filled = $creds[$field] !== '' ? 'پر' : 'خالی';
            $this->line("  {$field}: {$filled}");
        }
        $this->line('Callback نمونه: '.route('main.call-back'));
        $this->newLine();
        $this->comment('لاگ خطاها: storage/logs/laravel.log (جستجو: Sizpay)');

        return self::SUCCESS;
    }
}
