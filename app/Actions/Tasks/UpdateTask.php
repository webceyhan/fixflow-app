<?php

namespace App\Actions\Tasks;

use App\Models\Task;

class UpdateTask
{
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

        return $task;
    }
}
