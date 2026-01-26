<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Validation\Rule;

trait PriorityRules
{
    /**
     * Get the model class to use for validation.
     *
     * @return class-string<\Illuminate\Database\Eloquent\Model>
     */
    abstract protected function getModelClass(): string;

    /**
     * Get the validation rules used to validate model priorities.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function priorityRules(bool $required = false): array
    {
        $modelClass = $this->getModelClass();
        $model = new $modelClass;

        return [
            $required ? 'required' : 'nullable',
            Rule::enum($model->priority::class),
        ];
    }
}
