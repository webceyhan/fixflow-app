<?php

use App\Enums\Priority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Support\Carbon;

it('can initialize ticket', function () {
    $ticket = new Ticket();

    expect($ticket->id)->toBeNull();
    expect($ticket->description)->toBeNull();
    expect($ticket->priority)->toBe(Priority::Normal);
    expect($ticket->status)->toBe(TicketStatus::New);
    expect($ticket->created_at)->toBeNull();
    expect($ticket->updated_at)->toBeNull();
});

it('can create ticket', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->id)->toBeInt();
    expect($ticket->description)->toBeString();
    expect($ticket->priority)->toBe(Priority::Normal);
    expect($ticket->status)->toBe(TicketStatus::New);
    expect($ticket->created_at)->toBeInstanceOf(Carbon::class);
    expect($ticket->updated_at)->toBeInstanceOf(Carbon::class);
});

it('can create ticket of priority', function () {
    $ticket = Ticket::factory()->ofPriority(Priority::High)->create();

    expect($ticket->priority)->toBe(Priority::High);
});

it('can create ticket of status', function () {
    $ticket = Ticket::factory()->ofStatus(TicketStatus::Closed)->create();

    expect($ticket->status)->toBe(TicketStatus::Closed);
});

it('can update ticket', function () {
    $ticket = Ticket::factory()->create();

    $ticket->update([
        'description' => 'Repair iPhone 13 Pro',
        'priority' => Priority::High,
        'status' => TicketStatus::InProgress,
    ]);

    expect($ticket->description)->toBe('Repair iPhone 13 Pro');
    expect($ticket->priority)->toBe(Priority::High);
    expect($ticket->status)->toBe(TicketStatus::InProgress);
});

it('can delete ticket', function () {
    $ticket = Ticket::factory()->create();

    $ticket->delete();

    expect(Ticket::find($ticket->id))->toBeNull();
});

// Priority ////////////////////////////////////////////////////////////////////////////////////////

it('can filter tickets by priority scope', function (Priority $priority) {
    Ticket::factory()->ofPriority($priority)->create();

    expect(Ticket::ofPriority($priority)->count())->toBe(1);
    expect(Ticket::ofPriority($priority)->first()->priority)->toBe($priority);
})->with(Priority::cases());

it('can sort tickets by prioritized scope', function () {
    Ticket::factory()->create();
    Ticket::factory()->ofPriority(Priority::Low)->create();
    Ticket::factory()->ofPriority(Priority::High)->create();

    expect(Ticket::prioritized()->get()->map->priority->all())->toBe([
        Priority::High,
        Priority::Normal,
        Priority::Low,
    ]);
});

// Status ////////////////////////////////////////////////////////////////////////////////////////

it('can filter tickets by status scope', function (TicketStatus $status) {
    Ticket::factory()->ofStatus($status)->create();

    expect(Ticket::ofStatus($status)->count())->toBe(1);
    expect(Ticket::ofStatus($status)->first()->status)->toBe($status);
})->with(TicketStatus::cases());
