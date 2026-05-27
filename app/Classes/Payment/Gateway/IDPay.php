<?php


namespace App\Classes\Payment\Gateway;


use App\Classes\Error;
use App\Classes\Payment\GatewayInterface;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class IDPay implements GatewayInterface
{
    private $http;
    private $call_back;
    private $transaction;

    const PAY_URL = 'https://api.idpay.ir/v1.1/payment';
    const VERIFY_URL = 'https://api.idpay.ir/v1.1/payment/verify';

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;

        $this->call_back = route('main.call-back');
        $headers = [
            'X-API-KEY' => config('idpay.api_key'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];

        if (config('idpay.sandbox_status')){
            $headers['X-SANDBOX'] = true;
        }

        $this->http = Http::withHeaders($headers);
    }

    public function pay(): string
    {
        $response = $this->http->post(self::PAY_URL, [
            'order_id' => $this->transaction->id,
            'amount' => $this->transaction->price * 10,
            'callback' => $this->call_back,
        ]);

        $response = json_decode($response->body(), true);
        if (! isset($response['id'])){
            Error::catch(new \Exception($response['error_message']));

            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
        $this->transaction->update(['code' => $response['id']]);

        return $response['link'];
    }

    public function verify(): bool
    {
        $response = $this->http->post(self::VERIFY_URL, [
            'id' => $this->transaction->code,
            'order_id' => $this->transaction->id
        ]);

        $response = json_decode($response->body(), true);
        if (in_array($response['status'], [100, 101])){
            $this->transaction->update(['reference' => $response['track_id']]);
            return true;
        }

        return false;
    }
}
