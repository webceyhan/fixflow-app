<?php

namespace App\Http\Requests\Concerns;

trait ApprovalDateRules
{
    /**
     * Get the validation rules used to validate approval date fields.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function approvalDateRules(): array
    {
        return ['nullable', 'date'];
    }
}
