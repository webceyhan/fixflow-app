<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Validation\Rule;

trait ContactRules
{
    /**
     * Get the model class to use for validation.
     *
     * @return class-string<\Illuminate\Database\Eloquent\Model>
     */
    abstract protected function getModelClass(): string;

    /**
     * Get the validation rules used to validate model names.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate model emails.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function emailRules(?int $modelId = null, bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'string',
            'lowercase',
            'email',
            'max:255',
            $modelId === null
                ? Rule::unique($this->getModelClass())
                : Rule::unique($this->getModelClass())->ignore($modelId),
        ];
    }

    /**
     * Get the validation rules used to validate model phone numbers.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function phoneRules(?int $modelId = null, bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'string',
            'max:20',
            $modelId === null
                ? Rule::unique($this->getModelClass())
                : Rule::unique($this->getModelClass())->ignore($modelId),
        ];
    }

    /**
     * Get the validation rules used to validate model addresses.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function addressRules(): array
    {
        return ['nullable', 'string', 'max:500'];
    }
}
