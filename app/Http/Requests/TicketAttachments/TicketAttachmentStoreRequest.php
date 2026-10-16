<?php

namespace App\Http\Requests\TicketAttachments;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class TicketAttachmentStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                File::types([
                    'jpg',
                    'jpeg',
                    'png',
                    'webp',
                    'pdf',
                    'txt',
                    'doc',
                    'docx',
                    'xls',
                    'xlsx',
                ])->max(10 * 1024),
            ],
        ];
    }
}
