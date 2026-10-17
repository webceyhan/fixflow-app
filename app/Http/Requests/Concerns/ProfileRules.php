<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Contracts\Validation\Rule;

trait ProfileRules
{
    use UserRules;

    /**
     * Get the validation rules used to validate user profiles.
     *
     * @return array<string, array<int, Rule|array<mixed>|string>>
     */
    protected function profileRules(?int $userId = null): array
    {
        return [
            'name' => $this->nameRules(),
            'email' => $this->emailRules($userId),
            'phone' => $this->phoneRules($userId),
        ];
    }
}
