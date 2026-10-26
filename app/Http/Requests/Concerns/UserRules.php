<?php

namespace App\Http\Requests\Concerns;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

trait UserRules
{
    use ContactRules,
        NoteRules,
        StatusRules;

    /**
     * Get the model class to use for validation.
     *
     * @return class-string<User>
     */
    protected function getModelClass(): string
    {
        return User::class;
    }

    /**
     * Get all validation rules for user management.
     *
     * @return array<string, array<int, ValidationRule|array<mixed>|string>>
     */
    protected function userRules(?int $userId = null): array
    {
        return [
            'name' => $this->nameRules(),
            'email' => $this->emailRules($userId, true),
            'phone' => $this->phoneRules($userId, true),
            'note' => $this->noteRules(),
            'role' => $this->roleRules($userId !== null),
            'status' => $this->statusRules($userId !== null),
        ];
    }

    /**
     * Get the validation rules used to validate user roles.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function roleRules(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            Rule::enum(UserRole::class),
        ];
    }
}
