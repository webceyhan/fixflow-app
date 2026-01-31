<?php

namespace App\Http\Requests\Concerns;

use App\Enums\TransactionMethod;
use App\Models\Transaction;
use Illuminate\Validation\Rule;

trait TransactionRules
{
    use NoteRules, TypeRules;

    /**
     * Get the model class to use for validation.
     *
     * @return class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected function getModelClass(): string
    {
        return Transaction::class;
    }

    /**
     * Get all validation rules for transaction management.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function transactionRules(?int $transactionId = null): array
    {
        return [
            'amount' => $this->amountRules(),
            'note' => $this->noteRules(),
            'method' => $this->methodRules(),
            'type' => $this->typeRules(required: true),
        ];
    }

    /**
     * Get the validation rules used to validate transaction amounts.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function amountRules(): array
    {
        return ['required', 'numeric', 'min:0'];
    }

    /**
     * Get the validation rules used to validate transaction methods.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function methodRules(): array
    {
        return ['required', Rule::enum(TransactionMethod::class)];
    }
}
