<?php

namespace App\Actions\Tasks;

use App\Models\Task;

class CreateTask
{
    public function handle(array $data): Task
    {
        return Task::create([
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
    }
}
