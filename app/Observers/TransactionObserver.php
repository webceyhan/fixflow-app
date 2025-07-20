<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        $transaction->load('invoice');
        $transaction->invoice->fillTransactionAmounts()->fillStatus()->save();
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        // Only update financials if amount or type changed
        if ($transaction->wasChanged(['amount', 'type'])) {
            $transaction->load('invoice');
            $transaction->invoice->fillTransactionAmounts()->fillStatus()->save();
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        $transaction->load('invoice');
        $transaction->invoice->fillTransactionAmounts()->fillStatus()->save();
    }
}
