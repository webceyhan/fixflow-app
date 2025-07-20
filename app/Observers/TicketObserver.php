<?php

namespace App\Observers;

use App\Models\Ticket;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        $ticket->load('device');
        $ticket->device->fillTicketCounts()->save();
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        // Only update counts if status changed
        if ($ticket->wasChanged(['status'])) {
            $ticket->load('device');
            $ticket->device->fillTicketCounts()->save();
        }
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        $ticket->load('device');
        $ticket->device->fillTicketCounts()->save();
    }
}
