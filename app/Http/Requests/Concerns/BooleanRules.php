<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Contracts\Validation\Rule;

trait BooleanRules
{
    /**
     * Get the validation rules used to validate boolean fields.
     *
     * @return array<int, Rule|array<mixed>|string>
     */
    protected function booleanRules(): array
    {
        return ['nullable', 'boolean'];
    }
}
