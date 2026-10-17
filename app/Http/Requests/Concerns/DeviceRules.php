<?php

namespace App\Http\Requests\Concerns;

use App\Models\Device;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

trait DeviceRules
{
    use NoteRules,
        StatusRules,
        TypeRules;

    /**
     * Get the model class to use for validation.
     *
     * @return class-string<Model>
     */
    protected function getModelClass(): string
    {
        return Device::class;
    }

    /**
     * Get all validation rules for device management.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function deviceRules(?int $deviceId = null): array
    {
        return [
            'model' => $this->modelRules(),
            'brand' => $this->brandRules(),
            'color' => $this->colorRules(),
            'imei' => $this->imeiRules($deviceId),
            'serial_number' => $this->serialNumberRules($deviceId),
            'passcode' => $this->passcodeRules(),
            'purchase_date' => $this->purchaseDateRules(),
            'warranty_expire_date' => $this->warrantyExpireDateRules(),
            'note' => $this->noteRules(),
            'type' => $this->typeRules($deviceId !== null),
            'status' => $this->statusRules($deviceId !== null),
        ];
    }

    /**
     * Get the validation rules used to validate device models.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function modelRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate device brands.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function brandRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate device colors.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function colorRules(): array
    {
        return ['nullable', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate IMEIs.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function imeiRules(?int $deviceId = null): array
    {
        return [
            'nullable',
            'string',
            'max:15',
            $deviceId === null
                ? Rule::unique(Device::class)
                : Rule::unique(Device::class)->ignore($deviceId),
        ];
    }

    /**
     * Get the validation rules used to validate serial numbers.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function serialNumberRules(?int $deviceId = null): array
    {
        return [
            'nullable',
            'string',
            'max:255',
            $deviceId === null
                ? Rule::unique(Device::class)
                : Rule::unique(Device::class)->ignore($deviceId),
        ];
    }

    /**
     * Get the validation rules used to validate passcodes.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passcodeRules(): array
    {
        return ['nullable', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate purchase dates.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function purchaseDateRules(): array
    {
        return ['nullable', 'date'];
    }

    /**
     * Get the validation rules used to validate warranty expire dates.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function warrantyExpireDateRules(): array
    {
        return ['nullable', 'date', 'after_or_equal:purchase_date'];
    }
}
