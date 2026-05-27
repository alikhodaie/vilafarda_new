<?php


namespace App\Classes\Payment\Gateway;


use App\Classes\Payment\GatewayInterface;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Wallet implements GatewayInterface
{
    public $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
        $this->check();
    }

    private function check()
    {
        if (auth()->user()->wallet < $this->transaction->price){
            throw ValidationException::withMessages([
                'wallet' => 'موجودی کیف پول شما کافی نیست. لطفا کیف پول خود را شارژ یا از روش پرداخت دیگری استفاده کنید'
            ]);
        }
    }

    public function pay(): string
    {
        $token = Str::random(50);
        $this->transaction->update(['code' => $token]);
        return route('main.call-back', ['status' => true, 'token' => $token]);
    }

    public function verify(): bool
    {
        // TODO: Implement verify() method.
    }
}
