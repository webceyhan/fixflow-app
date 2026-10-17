<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Contracts\Validation\Rule;

trait NoteRules
{
    /**
     * Get the validation rules used to validate model notes.
     *
     * @return array<int, Rule|array<mixed>|string>
     */
    protected function noteRules(): array
    {
        return ['nullable', 'string'];
    }
}
