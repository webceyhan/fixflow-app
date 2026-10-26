<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Contracts\Validation\ValidationRule;

trait AmountRules
{
    /**
     * Get the validation rules used to validate amounts.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function amountRules(): array
    {
        return ['required', 'numeric', 'min:0'];
    }
}
