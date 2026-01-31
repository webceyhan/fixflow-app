<?php

namespace App\Actions\Tasks;

use App\Actions\Invoices\SyncInvoiceTasks;
use App\Models\Task;

class UpdateTask
{
    public function __construct(
        protected SyncInvoiceTasks $syncInvoiceTasks,
    ) {}

    public function handle(Task $task, array $data): Task
    {
        $task->update([
            'assignee_id' => $data['assignee_id'] ?? null,
            'cost' => $data['cost'],
            'is_billable' => $data['is_billable'],
            'note' => $data['note'] ?? null,
            'priority' => $data['priority'],
            'type' => $data['type'],
            'status' => $data['status'],
            'approved_at' => $data['approved_at'] ?? null,
        ]);

        // Sync task total to invoice
        $this->syncInvoiceTasks->handle($task->ticket->invoice);

        return $task;
    }
}
