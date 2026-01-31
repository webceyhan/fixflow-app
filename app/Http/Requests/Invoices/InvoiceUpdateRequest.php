<?php

namespace App\Http\Requests\Invoices;

use App\Http\Requests\Concerns\InvoiceRules;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceUpdateRequest extends FormRequest
{
    use InvoiceRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->invoiceRules($this->invoice->id);
    }
}
