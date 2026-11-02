<?php

namespace App\Actions\Tickets;

use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;

class FetchTicketQrCode
{
    public function __construct(
        protected GenerateTicketQrCode $generateTicketQrCode,
    ) {}

    public function handle(Ticket $ticket): string
    {
        $path = GenerateTicketQrCode::pathFromTicket($ticket);

        if (Storage::disk()->exists($path)) {
            return $path;
        }

        return $this->generateTicketQrCode->handle($ticket);
    }
}
