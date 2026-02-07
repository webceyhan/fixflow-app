<?php

namespace App\Http\Requests\Concerns;

use App\Models\Order;

trait OrderRules
{
    use AmountRules,
        ApprovalDateRules,
        AssigneeRules,
        BooleanRules,
        DueDateRules,
        NoteRules,
        StatusRules;

    /**
     * Get the model class to use for validation.
     *
     * @return class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected function getModelClass(): string
    {
        return Order::class;
    }

    /**
     * Get all validation rules for order management.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function orderRules(?int $orderId = null): array
    {
        return [
            'assignee_id' => $this->assigneeIdRules(),
            'name' => $this->nameRules(),
            'url' => $this->urlRules(),
            'supplier' => $this->supplierRules(),
            'quantity' => $this->quantityRules(),
            'cost' => $this->amountRules(),
            'is_billable' => $this->booleanRules(),
            'due_date' => $this->dueDateRules(),
            'note' => $this->noteRules(),
            'status' => $this->statusRules($orderId !== null),
            'approved_at' => $this->approvalDateRules(),
        ];
    }

    /**
     * Get the validation rules used to validate order name.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate order url.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function urlRules(): array
    {
        return ['nullable', 'url', 'max:255'];
    }

    /**
     * Get the validation rules used to validate order supplier.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function supplierRules(): array
    {
        return ['nullable', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate order quantity.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function quantityRules(): array
    {
        return ['required', 'integer', 'min:1'];
    }
}
