<?php

namespace App\Http\Requests\Concerns;

trait AssigneeRules
{
    /**
     * Get the validation rules used to validate assignee IDs.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function assigneeIdRules(): array
    {
        return ['nullable', 'integer', 'exists:users,id'];
    }
}
