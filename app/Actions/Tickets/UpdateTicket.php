<?php

namespace App\Actions\Tickets;

use App\Models\Ticket;

class UpdateTicket
{
    public function handle(Ticket $ticket, array $data): Ticket
    {
        $ticket->update([
            'assignee_id' => $data['assignee_id'] ?? null,
            'title' => $data['title'],
            'note' => $data['note'] ?? null,
            'priority' => $data['priority'],
            'status' => $data['status'],
            'due_date' => $data['due_date'] ?? null,
        ]);

        return $ticket;
    }
}
