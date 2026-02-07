<?php

namespace App\Http\Requests\Concerns;

use App\Models\Task;

trait TaskRules
{
    use AmountRules,
        ApprovalDateRules,
        AssigneeRules,
        BooleanRules,
        NoteRules,
        PriorityRules,
        StatusRules,
        TypeRules;

    /**
     * Get the model class to use for validation.
     *
     * @return class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected function getModelClass(): string
    {
        return Task::class;
    }

    /**
     * Get all validation rules for task management.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function taskRules(?int $taskId = null): array
    {
        return [
            'assignee_id' => $this->assigneeIdRules(),
            'cost' => $this->amountRules(),
            'is_billable' => $this->booleanRules(),
            'note' => $this->noteRules(),
            'priority' => $this->priorityRules($taskId !== null),
            'type' => $this->typeRules($taskId !== null),
            'status' => $this->statusRules($taskId !== null),
            'approved_at' => $this->approvalDateRules(),
        ];
    }
}
