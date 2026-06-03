<?php

namespace App\Classes\Payment\Gateway;

use App\Classes\Error;
use App\Classes\Payment\GatewayInterface;
use App\Classes\Payment\SizpayClient;
use App\Models\Transaction;
use Exception;
use Illuminate\Validation\ValidationException;

class Sizpay implements GatewayInterface
{
    private Transaction $transaction;

    private string $callBack;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->callBack = route('main.call-back');
    }

    public function pay(): string
    {
        if (! SizpayClient::isExtensionAvailable()) {
            throw ValidationException::withMessages([
                'error' => __('text.sizpay_soap_required'),
            ]);
        }

        $client = SizpayClient::fromSettings();

        if (! $client->isConfigured()) {
            throw ValidationException::withMessages([
                'error' => __('text.sizpay_not_configured'),
            ]);
        }

        $amountRial = (int) ($this->transaction->price * 10);

        if ($amountRial < 1000) {
            throw ValidationException::withMessages([
                'error' => 'مبلغ تراکنش نامعتبر است. حداقل مبلغ 100 تومان (1000 ریال) می‌باشد.',
            ]);
        }

        $token = $client->getToken(
            $amountRial,
            (int) $this->transaction->id,
            $this->callBack,
            (string) $this->transaction->id
        );

        if ($token === null || $token === '') {
            Error::catch(new Exception('Sizpay GetToken2 returned empty token'), __CLASS__, __METHOD__, 'مشکل درگاه پرداخت سیزپی');

            throw ValidationException::withMessages([
                'error' => __('text.sizpay_connection_failed'),
            ]);
        }

        $this->transaction->update(['code' => $token]);

        return route('main.sizpay.checkout', $this->transaction);
    }

    public function verify(): bool
    {
        $token = request()->input('Token', '');

        if ($token === '' || $token !== $this->transaction->code) {
            return false;
        }

        $client = SizpayClient::fromSettings();

        if (! $client->confirm($token)) {
            return false;
        }

        $this->transaction->update(['reference' => $token]);

        return true;
    }
}
