<?php

use App\Enums\OrderStatus;
use App\Models\Concerns\Billable;
use App\Models\Concerns\HasApproval;
use App\Models\Concerns\HasProgress;
use App\Models\Concerns\HasStatus;
use App\Models\Order;
use App\Models\Ticket;
use App\Observers\OrderObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Order::class,
    concerns: [
        Billable::class,
        HasApproval::class,
        HasProgress::class,
        HasStatus::class,
    ],
    observers: [
        OrderObserver::class,
    ],
    defaults: [
        'quantity' => 1,
        'status' => OrderStatus::New,
    ],
    fillables: [
        'name',
        'url',
        'supplier',
        'quantity',
        'cost',
        'is_billable',
        'status',
        'approved_at',
    ],
    casts: [
        'quantity' => 'integer',
        'cost' => 'float',
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
