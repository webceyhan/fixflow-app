<?php

namespace App\Http\Requests\Tasks;

use App\Http\Requests\Concerns\TaskRules;
use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    use TaskRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->taskRules();
    }
}
