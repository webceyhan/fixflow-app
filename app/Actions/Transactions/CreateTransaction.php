<?php

namespace App\Actions\Transactions;

use App\Actions\Invoices\SyncInvoiceTransactions;
use App\Models\Transaction;

class CreateTransaction
{
    public function __construct(
        protected SyncInvoiceTransactions $syncInvoiceTransactions,
    ) {}

    public function handle(array $data): Transaction
    {
        $transaction = Transaction::create([
            'invoice_id' => $data['invoice_id'],
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
