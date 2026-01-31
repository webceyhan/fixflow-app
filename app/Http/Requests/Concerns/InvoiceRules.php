<?php

namespace App\Http\Requests\Concerns;

use App\Models\Invoice;

trait InvoiceRules
{
    use DueDateRules, NoteRules, StatusRules;

    /**
     * Get the model class to use for validation.
     *
     * @return class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected function getModelClass(): string
    {
        return Invoice::class;
    }

    /**
     * Get all validation rules for invoice management.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function invoiceRules(?int $invoiceId = null): array
    {
        return [
            'total' => $this->amountRules(),
            'discount_amount' => $this->amountRules(),
            'fee_amount' => $this->amountRules(),
            'paid_amount' => $this->amountRules(),
            'refunded_amount' => $this->amountRules(),
            'due_date' => $this->dueDateRules(),
            'note' => $this->noteRules(),
            'status' => $this->statusRules($invoiceId !== null),
        ];
    }

    /**
     * Get the validation rules used to validate invoice amounts.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function amountRules(): array
    {
        return ['required', 'numeric', 'min:0'];
    }
}
