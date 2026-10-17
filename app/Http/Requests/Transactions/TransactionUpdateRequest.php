<?php

namespace App\Http\Requests\Transactions;

use App\Http\Requests\Concerns\TransactionRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TransactionUpdateRequest extends FormRequest
{
    use TransactionRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->transactionRules($this->transaction->id);
    }
}
