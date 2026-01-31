<?php

namespace App\Actions\Tasks;

use App\Actions\Invoices\SyncInvoiceTasks;
use App\Models\Task;

class DeleteTask
{
    public function __construct(
        protected SyncInvoiceTasks $syncInvoiceTasks,
    ) {}

    public function handle(Task $task): void
    {
        $invoice = $task->ticket->invoice;

        $task->delete();

        // Sync task total to invoice
        $this->syncInvoiceTasks->handle($invoice);
    }
}
