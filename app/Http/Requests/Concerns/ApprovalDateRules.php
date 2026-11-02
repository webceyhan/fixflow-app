<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Contracts\Validation\ValidationRule;

trait ApprovalDateRules
{
    /**
     * Get the validation rules used to validate approval date fields.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function approvalDateRules(): array
    {
        return ['nullable', 'date'];
    }
}
