<?php

namespace App\Actions\Tickets;

use App\Models\Ticket;

class CreateTicket
{
    public function handle(array $data): Ticket
    {
        return Ticket::create([
            'device_id' => $data['device_id'],
            'assignee_id' => $data['assignee_id'] ?? null,
            'title' => $data['title'],
            'note' => $data['note'] ?? null,
            'priority' => $data['priority'] ?? null,
            'status' => $data['status'] ?? null,
            'due_date' => $data['due_date'] ?? null,
        ]);
    }
}
