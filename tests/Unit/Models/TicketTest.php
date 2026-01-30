<?php

use App\Enums\Priority;
use App\Enums\TicketStatus;
use App\Models\Concerns\Assignable;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasDueDate;
use App\Models\Concerns\HasPriority;
use App\Models\Concerns\HasStatus;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Task;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Ticket::class,
    concerns: [
        Assignable::class,
        HasDueDate::class,
        HasPriority::class,
        HasStatus::class,
        HasActiveStatus::class,
    ],
    defaults: [
        'priority' => Priority::Medium,
        'status' => TicketStatus::Created,
    ],
    fillables: [
        'device_id',
        'assignee_id',
        'title',
        'note',
        'priority',
        'status',
        'due_date',
    ],
    hiddens: [
        // No hidden attributes
    ],
    casts: [
        'priority' => Priority::class,
        'status' => TicketStatus::class,
        'due_date' => 'date',
    ],
    relations: [
        'device' => BelongsTo::class,
        'customer' => HasOneThrough::class,
        'assignee' => BelongsTo::class,
    ]
);

// RELATION TESTS //////////////////////////////////////////////////////////////////////////////////

it('belongs to device relationship', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();
    $ticket = Ticket::factory()->forDevice($device)->create();

    // Assert
    expect($ticket->device)->toBeInstanceOf(Device::class);
    expect($ticket->device->id)->toBe($device->id);
});

it('has customer through device relationship', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();
    $ticket = Ticket::factory()->forDevice($device)->create();

    // Assert
    expect($ticket->customer)->toBeInstanceOf(Customer::class);
    expect($ticket->customer->id)->toBe($customer->id);
});

it('has many tasks relationship', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    Task::factory()->count(3)->forTicket($ticket)->create();

    $otherTicket = Ticket::factory()->create();
    Task::factory()->forTicket($otherTicket)->create(); // Not this ticket's task

    // Assert
    expect($ticket->tasks)->toHaveCount(3);
    expect($ticket->tasks->first())->toBeInstanceOf(Task::class);
    expect($ticket->tasks->first()->ticket_id)->toBe($ticket->id);
});

// SCOPE TESTS /////////////////////////////////////////////////////////////////////////////////////

// METHOD TESTS ////////////////////////////////////////////////////////////////////////////////////
