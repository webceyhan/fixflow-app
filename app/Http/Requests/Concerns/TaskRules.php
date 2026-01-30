<?php

namespace App\Http\Requests\Concerns;

use App\Models\Task;

trait TaskRules
{
    use AssigneeRules, NoteRules, PriorityRules, StatusRules, TypeRules;

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
            'cost' => $this->costRules(),
            'is_billable' => $this->isBillableRules(),
            'note' => $this->noteRules(),
            'priority' => $this->priorityRules($taskId !== null),
            'type' => $this->typeRules($taskId !== null),
            'status' => $this->statusRules($taskId !== null),
            'approved_at' => $this->approvedAtRules(),
        ];
    }

    /**
     * Get the validation rules used to validate task cost.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function costRules(): array
    {
        return ['required', 'numeric', 'min:0'];
    }

    /**
     * Get the validation rules used to validate task billable flag.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function isBillableRules(): array
    {
        return ['required', 'boolean'];
    }

    /**
     * Get the validation rules used to validate task approval date.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function approvedAtRules(): array
    {
        return ['nullable', 'date'];
    }
}
