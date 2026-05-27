<?php


namespace App\Classes\Payment;


use App\Models\Transaction;

interface GatewayInterface
{
    /**
     * Set transaction property
     *
     * GatewayInterface constructor.
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction);

    /**
     * return payment url
     *
     * @return string return url that want to redirect to
     */
    public function pay(): string;

    /**
     * verify payment
     *
     * @return bool
     */
    public function verify(): bool;
}
