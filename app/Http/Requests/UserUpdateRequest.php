<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    const NAME = 'name';
    const EMAIL = 'email';
    const PHONE = 'phone';
    const ROLE = 'role';
    const STATUS = 'status';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            self::NAME => [
                'nullable',
                'string',
                'max:255'
            ],
            self::EMAIL => [
                'required',
                'email',
                'lowercase',
                'max:255',
                Rule::unique(User::class)->ignore($userId),
            ],
            self::PHONE => [
                'nullable',
                'string',
                Rule::unique(User::class)->ignore($userId),
            ],
            self::ROLE => [
                'nullable',
                Rule::in(UserRole::values())
            ],
            self::STATUS => [
                'nullable',
                Rule::in(UserStatus::values())
            ],
        ];
    }
}
