<?php

namespace App\Http\Requests\Concerns;

use App\Models\Invoice;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

trait InvoiceRules
{
    use AmountRules,
        DueDateRules,
        NoteRules,
        StatusRules;

    /**
     * Get the model class to use for validation.
     *
     * @return class-string<Model>
     */
    protected function getModelClass(): string
    {
        return Invoice::class;
    }

    /**
     * Get all validation rules for invoice management.
     *
     * @return array<string, array<int, Rule|array<mixed>|string>>
     */
    protected function invoiceRules(?int $invoiceId = null): array
    {
        return [
            'total' => $this->amountRules(),
            'due_date' => $this->dueDateRules(),
            'note' => $this->noteRules(),
            'status' => $this->statusRules($invoiceId !== null),
        ];
    }
}
