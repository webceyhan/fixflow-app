<?php

namespace App\Actions\Invoices;

use App\Enums\TransactionType;
use App\Models\Invoice;

class SyncInvoiceTransactions
{
    public function handle(Invoice $invoice): void
    {
        $invoice->forceFill([
            'paid_amount' => $invoice->transactions()
                ->whereIn('type', [TransactionType::Payment, TransactionType::Deposit])
                ->sum('amount'),
            'refunded_amount' => $invoice->transactions()
                ->where('type', TransactionType::Refund)
                ->sum('amount'),
        ])->save();
    }
}
