<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Contracts\Validation\Rule;

trait AssigneeRules
{
    /**
     * Get the validation rules used to validate assignee IDs.
     *
     * @return array<int, Rule|array<mixed>|string>
     */
    protected function assigneeIdRules(): array
    {
        return ['nullable', 'integer', 'exists:users,id'];
    }
}
