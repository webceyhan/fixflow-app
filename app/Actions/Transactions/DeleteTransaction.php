<?php

namespace App\Actions\Transactions;

use App\Actions\Invoices\SyncInvoiceTransactions;
use App\Models\Transaction;

class DeleteTransaction
{
    public function __construct(
        protected SyncInvoiceTransactions $syncInvoiceTransactions,
    ) {}

    public function handle(Transaction $transaction): void
    {
        $invoice = $transaction->invoice;

        $transaction->delete();

        // Sync invoice paid/refunded amounts
        $this->syncInvoiceTransactions->handle($invoice);
    }
}
