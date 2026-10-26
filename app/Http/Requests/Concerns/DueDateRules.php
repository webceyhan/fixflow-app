<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Contracts\Validation\ValidationRule;

trait DueDateRules
{
    /**
     * Get the validation rules used to validate due dates.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function dueDateRules(): array
    {
        return ['nullable', 'date', 'after_or_equal:today'];
    }
}
