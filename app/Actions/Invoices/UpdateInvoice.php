<?php

namespace App\Actions\Invoices;

use App\Models\Invoice;

class UpdateInvoice
{
    public function handle(Invoice $invoice, array $data): Invoice
    {
        $invoice->update([
            'total' => $data['total'],
            'discount_amount' => $data['discount_amount'],
            'fee_amount' => $data['fee_amount'],
            'paid_amount' => $data['paid_amount'],
            'refunded_amount' => $data['refunded_amount'],
            'due_date' => $data['due_date'],
            'note' => $data['note'] ?? null,
            'status' => $data['status'],
        ]);

        return $invoice;
    }
}
