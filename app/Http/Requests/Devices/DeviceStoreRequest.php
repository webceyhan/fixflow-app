<?php

namespace App\Http\Requests\Devices;

use App\Http\Requests\Concerns\DeviceRules;
use Illuminate\Foundation\Http\FormRequest;

class DeviceStoreRequest extends FormRequest
{
    use DeviceRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->deviceRules();
    }
}
