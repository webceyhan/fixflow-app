<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends UserUpdateRequest
{
    const PASSWORD = 'password';

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // auto-generate random password
        $this->merge([
            'password' => Hash::make(Str::password())
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            self::PASSWORD => [
                'required',
                Password::defaults()
            ],
        ];
    }
}
