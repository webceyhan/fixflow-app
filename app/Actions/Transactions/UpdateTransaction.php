<?php

namespace App\Actions\Transactions;

use App\Actions\Invoices\SyncInvoiceTransactions;
use App\Models\Transaction;

class UpdateTransaction
{
    public function __construct(
        protected SyncInvoiceTransactions $syncInvoiceTransactions,
    ) {}

    public function handle(Transaction $transaction, array $data): Transaction
    {
        $transaction->update([
            'amount' => $data['amount'],
            'note' => $data['note'] ?? null,
            'method' => $data['method'],
            'type' => $data['type'],
        ]);

        // Sync invoice paid/refunded amounts
        $this->syncInvoiceTransactions->handle($transaction->invoice);

        return $transaction;
    }
}
