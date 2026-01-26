<?php

namespace App\Http\Requests\Concerns;

trait DueDateRules
{
    /**
     * Get the validation rules used to validate due dates.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function dueDateRules(): array
    {
        return ['nullable', 'date', 'after_or_equal:today'];
    }
}
