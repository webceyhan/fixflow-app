<?php

namespace App\Actions\Tasks;

use App\Actions\Invoices\SyncInvoiceTasks;
use App\Models\Task;

class CreateTask
{
    public function __construct(
        protected SyncInvoiceTasks $syncInvoiceTasks,
    ) {}

    public function handle(array $data): Task
    {
        $task = Task::create([
            'ticket_id' => $data['ticket_id'],
            'assignee_id' => $data['assignee_id'] ?? null,
            'cost' => $data['cost'] ?? 0,
            'is_billable' => $data['is_billable'] ?? true,
            'note' => $data['note'] ?? null,
            'priority' => $data['priority'] ?? null,
            'type' => $data['type'] ?? null,
            'status' => $data['status'] ?? null,
            'approved_at' => $data['approved_at'] ?? null,
        ]);

        // Sync task total to invoice
        $this->syncInvoiceTasks->handle($task->ticket->invoice);

        return $task;
    }
}
