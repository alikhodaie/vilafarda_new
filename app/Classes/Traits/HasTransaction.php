<?php

namespace App\Classes\Traits;

use App\Models\Transaction;

trait HasTransaction
{
    public function transactions()
    {
        return $this->morphToMany(Transaction::class, 'transactionable', 'transactionable');
    }

    public function attachTransaction(Transaction $transaction)
    {
        $this->transactions()->attach($transaction);
    }
}
