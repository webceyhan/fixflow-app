<?php

use App\Actions\Tickets\GenerateTicketQrCode;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('renders ticket qr code as svg for authenticated users', function () {
    Storage::fake();

    $user = User::factory()->create();
    $ticket = Ticket::factory()->create();

    $response = actingAs($user)->get(route('tickets.qr-code', $ticket));

    $response
        ->assertSuccessful()
        ->assertHeader('Content-Type', 'image/svg+xml')
        ->assertSee('<svg', false);

    Storage::assertExists(GenerateTicketQrCode::pathFromTicket($ticket));
});

it('requires authentication to render ticket qr code', function () {
    $ticket = Ticket::factory()->create();

    get(route('tickets.qr-code', $ticket))
        ->assertRedirect(route('login'));
});
