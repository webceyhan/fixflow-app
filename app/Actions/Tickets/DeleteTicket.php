<?php

namespace App\Actions\Tickets;

use App\Models\Ticket;

class DeleteTicket
{
    public function handle(Ticket $ticket): bool
    {
        return $ticket->delete();
    }
}
