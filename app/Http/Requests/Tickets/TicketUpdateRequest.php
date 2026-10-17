<?php

namespace App\Http\Requests\Tickets;

use App\Http\Requests\Concerns\TicketRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TicketUpdateRequest extends FormRequest
{
    use TicketRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->ticketRules($this->ticket->id);
    }
}
