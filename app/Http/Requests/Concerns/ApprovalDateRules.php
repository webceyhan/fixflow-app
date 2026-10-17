<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Contracts\Validation\Rule;

trait ApprovalDateRules
{
    /**
     * Get the validation rules used to validate approval date fields.
     *
     * @return array<int, Rule|array<mixed>|string>
     */
    protected function approvalDateRules(): array
    {
        return ['nullable', 'date'];
    }
}
