<?php

namespace App\Actions\Tickets;

use App\Actions\Invoices\CreateInvoice;
use App\Models\Ticket;

class CreateTicket
{
    public function __construct(
        protected CreateInvoice $createInvoice,
    ) {}

    public function handle(array $data): Ticket
    {
        $ticket = Ticket::create([
            'device_id' => $data['device_id'],
            'assignee_id' => $data['assignee_id'] ?? null,
            'title' => $data['title'],
            'note' => $data['note'] ?? null,
            'priority' => $data['priority'] ?? null,
            'status' => $data['status'] ?? null,
            'due_date' => $data['due_date'] ?? null,
        ]);

        // Automatically create a draft invoice for the ticket
        $this->createInvoice->handle([
            'ticket_id' => $ticket->id,
            'due_date' => $ticket->due_date,
        ]);

        return $ticket;
    }
}
