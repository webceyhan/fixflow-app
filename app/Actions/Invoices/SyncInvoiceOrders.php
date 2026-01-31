<?php

namespace App\Actions\Invoices;

use App\Models\Invoice;

class SyncInvoiceOrders
{
    public function handle(?Invoice $invoice): void
    {
        $invoice?->forceFill([
            'order_total' => $invoice->ticket->orders()->billable()->sum('cost'),
        ])->save();
    }
}
