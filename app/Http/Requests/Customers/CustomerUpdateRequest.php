<?php

namespace App\Http\Requests\Customers;

use App\Http\Requests\Concerns\CustomerRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
{
    use CustomerRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->customerRules($this->customer->id);
    }
}
