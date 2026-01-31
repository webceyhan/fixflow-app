<?php

namespace App\Actions\Invoices;

use App\Models\Invoice;

class CreateInvoice
{
    public function handle(array $data): Invoice
    {
        return Invoice::create([
            'ticket_id' => $data['ticket_id'],
            'due_date' => $data['due_date'] ?? now()->addDays(30),
        ]);
    }
}
