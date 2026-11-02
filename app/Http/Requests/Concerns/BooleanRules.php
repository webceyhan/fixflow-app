<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Contracts\Validation\ValidationRule;

trait BooleanRules
{
    /**
     * Get the validation rules used to validate boolean fields.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function booleanRules(): array
    {
        return ['nullable', 'boolean'];
    }
}
