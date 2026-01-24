<?php

namespace App\Http\Requests\Customers;

use App\Http\Requests\Concerns\CustomerRules;
use Illuminate\Foundation\Http\FormRequest;

class CustomerStoreRequest extends FormRequest
{
    use CustomerRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->customerRules();
    }
}
