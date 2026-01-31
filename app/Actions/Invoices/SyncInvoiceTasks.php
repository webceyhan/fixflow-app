<?php

namespace App\Actions\Invoices;

use App\Models\Invoice;

class SyncInvoiceTasks
{
    public function handle(?Invoice $invoice): void
    {
        $invoice?->forceFill([
            'task_total' => $invoice->ticket->tasks()->billable()->sum('cost'),
        ])->save();
    }
}
