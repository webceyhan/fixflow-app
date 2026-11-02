<?php

namespace App\Actions\Invoices;

use App\Models\Invoice;

class UpdateInvoice
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Invoice $invoice, array $data): Invoice
    {
        $invoice->update([
            'total' => $data['total'],
            'due_date' => $data['due_date'],
            'note' => $data['note'] ?? null,
            'status' => $data['status'],
        ]);

        return $invoice;
    }
}
