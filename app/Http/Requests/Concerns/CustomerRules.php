<?php

namespace App\Http\Requests\Concerns;

use App\Enums\CustomerType;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

trait CustomerRules
{
    use ContactRules,
        NoteRules,
        StatusRules,
        TypeRules;

    /**
     * Get the model class to use for validation.
     *
     * @return class-string<Model>
     */
    protected function getModelClass(): string
    {
        return Customer::class;
    }

    /**
     * Get all validation rules for customer management.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function customerRules(?int $customerId = null): array
    {
        return [
            'name' => $this->nameRules(),
            'company' => $this->companyRules($customerId),
            'tax_number' => $this->taxNumberRules($customerId),
            'email' => $this->emailRules($customerId),
            'phone' => $this->phoneRules($customerId),
            'address' => $this->addressRules(),
            'note' => $this->noteRules(),
            'type' => $this->typeRules($customerId !== null),
            'status' => $this->statusRules($customerId !== null),
        ];
    }

    /**
     * Get the validation rules used to validate company names.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function companyRules(?int $customerId = null): array
    {
        return [
            Rule::requiredIf($this->input('type') === CustomerType::Business->value),
            'nullable',
            'string',
            'max:255',
            $customerId === null
                ? Rule::unique(Customer::class)
                : Rule::unique(Customer::class)->ignore($customerId),
        ];
    }

    /**
     * Get the validation rules used to validate tax numbers.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function taxNumberRules(?int $customerId = null): array
    {
        return [
            Rule::requiredIf($this->input('type') === CustomerType::Business->value),
            'nullable',
            'string',
            'max:50',
            $customerId === null
                ? Rule::unique(Customer::class)
                : Rule::unique(Customer::class)->ignore($customerId),
        ];
    }
}
