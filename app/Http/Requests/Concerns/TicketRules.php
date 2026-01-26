<?php

namespace App\Http\Requests\Concerns;

use App\Models\Ticket;

trait TicketRules
{
    use AssigneeRules, DueDateRules, NoteRules, PriorityRules, StatusRules;

    /**
     * Get the model class to use for validation.
     *
     * @return class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected function getModelClass(): string
    {
        return Ticket::class;
    }

    /**
     * Get all validation rules for ticket management.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function ticketRules(?int $ticketId = null): array
    {
        return [
            'assignee_id' => $this->assigneeIdRules(),
            'title' => $this->titleRules(),
            'note' => $this->noteRules(),
            'priority' => $this->priorityRules($ticketId !== null),
            'status' => $this->statusRules($ticketId !== null),
            'due_date' => $this->dueDateRules(),
        ];
    }

    /**
     * Get the validation rules used to validate ticket titles.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function titleRules(): array
    {
        return ['required', 'string', 'max:255'];
    }
}
