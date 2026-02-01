<?php

namespace App\Http\Requests\Concerns;

trait AmountRules
{
    /**
     * Get the validation rules used to validate amounts.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function amountRules(): array
    {
        return ['required', 'numeric', 'min:0'];
    }
}
