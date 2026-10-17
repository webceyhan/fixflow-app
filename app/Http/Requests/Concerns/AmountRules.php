<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Contracts\Validation\Rule;

trait AmountRules
{
    /**
     * Get the validation rules used to validate amounts.
     *
     * @return array<int, Rule|array<mixed>|string>
     */
    protected function amountRules(): array
    {
        return ['required', 'numeric', 'min:0'];
    }
}
