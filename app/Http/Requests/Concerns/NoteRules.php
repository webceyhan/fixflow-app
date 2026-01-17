<?php

namespace App\Http\Requests\Concerns;

trait NoteRules
{
    /**
     * Get the validation rules used to validate model notes.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function noteRules(): array
    {
        return ['nullable', 'string'];
    }
}
