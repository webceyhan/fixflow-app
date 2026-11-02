<?php

namespace App\Actions\Tickets;

use App\Models\Ticket;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Storage;

class GenerateTicketQrCode
{
    public function handle(Ticket $ticket): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(280),
            new SvgImageBackEnd
        );
        $writer = new Writer($renderer);
        $svg = $writer->writeString(route('tickets.show', $ticket));

        $path = self::pathFromTicket($ticket);
        Storage::disk()->put($path, $svg);

        return $path;
    }

    public static function pathFromTicket(Ticket $ticket): string
    {
        return "tickets/{$ticket->id}/qrcode.svg";
    }
}
