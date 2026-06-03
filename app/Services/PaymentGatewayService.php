<?php

namespace App\Services;

use App\Classes\Payment\SizpayClient;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;

class PaymentGatewayService
{
    public const SETTING_PREFIX = 'payment:gateway-';

    public const SETTING_SUFFIX = '-enabled';

    public static function settingKey(string $gateway): string
    {
        return self::SETTING_PREFIX.$gateway.self::SETTING_SUFFIX;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function availableForCheckout(?User $user = null, ?Order $order = null): array
    {
        $user ??= auth()->user();
        $payableAmount = $order ? (float) $order->payable_price : null;
        $gateways = [];

        foreach (Transaction::GATEWAY as $key => $meta) {
            if (! $this->isEnabled($key)) {
                continue;
            }

            if (! $this->isConfigured($key)) {
                continue;
            }

            if ($key === Transaction::WALLET && ! $this->walletEligible($user, $payableAmount)) {
                continue;
            }

            $gateways[$key] = array_merge($meta, [
                'key' => $key,
                'icon' => $this->icon($key),
            ]);
        }

        return $gateways;
    }

    public function isEnabled(string $gateway): bool
    {
        if (! array_key_exists($gateway, Transaction::GATEWAY)) {
            return false;
        }

        return settingBoolean(self::settingKey($gateway), $this->defaultEnabled($gateway));
    }

    public function isConfigured(string $gateway): bool
    {
        switch ($gateway) {
            case Transaction::ZARINPAL:
                if (settingBoolean('zarinpal:sandbox')) {
                    return true;
                }

                return trim((string) setting('zarinpal:merchant-id', '')) !== '';

            case Transaction::IDPAY:
                return trim((string) setting('idpay:api-key', '')) !== ''
                    || trim((string) config('idpay.api_key', '')) !== '';

            case Transaction::SIZPAY:
                return SizpayClient::fromSettings()->isConfigured();

            case Transaction::WALLET:
                return true;

            default:
                return false;
        }
    }

    public function assertSelectable(string $gateway, ?User $user = null, ?Order $order = null): void
    {
        if (! array_key_exists($gateway, $this->availableForCheckout($user, $order))) {
            abort(422, __('text.payment_gateway_unavailable'));
        }
    }

    private function defaultEnabled(string $gateway): bool
    {
        return $gateway === Transaction::ZARINPAL;
    }

    private function walletEligible(?User $user, ?float $payableAmount = null): bool
    {
        if (! $user) {
            return false;
        }

        $balance = (float) ($user->wallet ?? 0);

        if ($payableAmount !== null) {
            return $balance >= $payableAmount;
        }

        return $balance > 0;
    }

    private function icon(string $gateway): string
    {
        return match ($gateway) {
            Transaction::ZARINPAL => 'bi-credit-card-2-front',
            Transaction::IDPAY => 'bi-bank2',
            Transaction::SIZPAY => 'bi-phone',
            Transaction::WALLET => 'bi-wallet2',
            default => 'bi-cash-stack',
        };
    }
}
