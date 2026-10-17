<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

trait StatusRules
{
    /**
     * Get the model class to use for validation.
     *
     * @return class-string<Model>
     */
    abstract protected function getModelClass(): string;

    /**
     * Get the validation rules used to validate model statuses.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function statusRules(bool $required = false): array
    {
        $modelClass = $this->getModelClass();
        $model = new $modelClass;

        return [
            $required ? 'required' : 'nullable',
            Rule::enum($model->status::class),
        ];
    }
}
