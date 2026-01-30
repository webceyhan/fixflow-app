<?php

use App\Enums\OrderStatus;
use App\Models\Concerns\Approvable;
use App\Models\Concerns\Assignable;
use App\Models\Concerns\Billable;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasDueDate;
use App\Models\Concerns\HasStatus;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Order::class,
    concerns: [
        Approvable::class,
        Assignable::class,
        Billable::class,
        HasActiveStatus::class,
        HasDueDate::class,
        HasStatus::class,
    ],
    defaults: [
        'quantity' => 1,
        'cost' => 0,
        'is_billable' => true,
        'status' => OrderStatus::Created,
    ],
    fillables: [
        'ticket_id',
        'assignee_id',
        'name',
        'url',
        'supplier',
        'quantity',
        'cost',
        'is_billable',
        'due_date',
        'note',
        'status',
        'approved_at',
    ],
    hiddens: [
        // No hidden attributes
    ],
    casts: [
        'quantity' => 'integer',
        'cost' => 'float',
        'is_billable' => 'boolean',
        'status' => OrderStatus::class,
        'due_date' => 'date',
        'approved_at' => 'datetime',
    ],
    relations: [
        'ticket' => BelongsTo::class,
        'assignee' => BelongsTo::class,
    ]
);

// RELATION TESTS //////////////////////////////////////////////////////////////////////////////////

it('belongs to ticket relationship', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    $order = Order::factory()->forTicket($ticket)->create();

    // Assert
    expect($order->ticket)->toBeInstanceOf(Ticket::class);
    expect($order->ticket->id)->toBe($ticket->id);
});

// SCOPE TESTS /////////////////////////////////////////////////////////////////////////////////////

// METHOD TESTS ////////////////////////////////////////////////////////////////////////////////////
