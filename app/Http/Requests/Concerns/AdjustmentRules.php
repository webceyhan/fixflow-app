<?php

namespace App\Http\Requests\Concerns;

use App\Enums\AdjustmentReason;
use App\Models\Adjustment;
use Illuminate\Validation\Rule;

trait AdjustmentRules
{
    use AmountRules, BooleanRules, NoteRules;

    /**
     * Get the model class to use for validation.
     *
     * @return class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected function getModelClass(): string
    {
        return Adjustment::class;
    }

    /**
     * Get all validation rules for adjustment management.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function adjustmentRules(?int $adjustmentId = null): array
    {
        return [
            'amount' => $this->amountRules(),
            'is_percent' => $this->booleanRules(),
            'note' => $this->noteRules(),
            'reason' => $this->reasonRules(required: true),
        ];
    }

    /**
     * Get the validation rules used to validate adjustment reasons.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function reasonRules(bool $required = false): array
    {
        return [
            ...($required ? ['required'] : ['sometimes']),
            Rule::enum(AdjustmentReason::class),
        ];
    }
}
