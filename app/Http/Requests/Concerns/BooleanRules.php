<?php

namespace App\Http\Requests\Concerns;

trait BooleanRules
{
    /**
     * Get the validation rules used to validate boolean fields.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function booleanRules(): array
    {
        return ['nullable', 'boolean'];
    }
}
