<?php

namespace App\Http\Requests\Adjustments;

use App\Http\Requests\Concerns\AdjustmentRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdjustmentUpdateRequest extends FormRequest
{
    use AdjustmentRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->adjustmentRules($this->adjustment->id);
    }
}
