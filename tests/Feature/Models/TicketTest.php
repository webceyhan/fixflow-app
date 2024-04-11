<?php

use App\Models\Ticket;
use Illuminate\Support\Carbon;

it('can initialize ticket', function () {
    $ticket = new Ticket();

    expect($ticket->id)->toBeNull();
    expect($ticket->description)->toBeNull();
    expect($ticket->created_at)->toBeNull();
    expect($ticket->updated_at)->toBeNull();
});

it('can create ticket', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->id)->toBeInt();
    expect($ticket->description)->toBeString();
    expect($ticket->created_at)->toBeInstanceOf(Carbon::class);
    expect($ticket->updated_at)->toBeInstanceOf(Carbon::class);
});

it('can update ticket', function () {
    $ticket = Ticket::factory()->create();

    $ticket->update([
        'description' => 'Repair iPhone 13 Pro',
    ]);

    expect($ticket->description)->toBe('Repair iPhone 13 Pro');
});

it('can delete ticket', function () {
    $ticket = Ticket::factory()->create();

    $ticket->delete();

    expect(Ticket::find($ticket->id))->toBeNull();
});
