<?php

namespace App\Http\Controllers;

use App\Actions\Tickets\FetchTicketQrCode;
use App\Models\Ticket;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Storage;

class TicketQrCodeController extends Controller
{
    public function show(Ticket $ticket, FetchTicketQrCode $fetchTicketQrCode): HttpResponse
    {
        $path = $fetchTicketQrCode->handle($ticket);
        $disk = Storage::disk();

        abort_unless($disk->exists($path), 404);

        return response($disk->get($path), 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'private, max-age=300',
        ]);
    }
}
