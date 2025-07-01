<?php

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a ticket with valid attributes', function () {
    // Arrange
    $ticket = Ticket::factory()->create();

    // Assert
    expect($ticket->device_id)->not->toBeNull();
    expect($ticket->title)->not->toBeEmpty();
    expect($ticket->description)->not->toBeEmpty();
    expect($ticket->priority)->toBeInstanceOf(TicketPriority::class);
    expect($ticket->status)->toBeInstanceOf(TicketStatus::class);
    expect($ticket->due_date)->not->toBeNull();
});

it('can create a ticket with an assignee', function () {
    // Arrange
    $user = User::factory()->create();
    $ticket = Ticket::factory()->forAssignee($user)->create();

    // Assert
    expect($ticket->assignee_id)->toBe($user->id);
    expect($ticket->assignee)->toBeInstanceOf(User::class);
});

it('can create a ticket of priority', function () {
    // Arrange
    $ticket = Ticket::factory()->ofPriority(TicketPriority::High)->create();

    // Assert
    expect($ticket->priority)->toBe(TicketPriority::High);
});

it('can create a ticket of status', function () {
    // Arrange
    $ticket = Ticket::factory()->ofStatus(TicketStatus::InProgress)->create();

    // Assert
    expect($ticket->status)->toBe(TicketStatus::InProgress);
});

it('can create an overdue ticket', function () {
    // Arrange
    $ticket = Ticket::factory()->overdue()->create();

    // Assert
    expect($ticket->due_date->isPast())->toBeTrue();
    expect($ticket->status)->not->toBe(TicketStatus::Closed);
});

it('can update a ticket', function () {
    // Arrange
    $ticket = Ticket::factory()->create();

    // Act
    $ticket->update([
        'title' => 'Updated Ticket Title',
        'description' => 'Updated ticket description',
        'priority' => TicketPriority::High,
        'status' => TicketStatus::InProgress,
        'due_date' => now()->addMonth(),
    ]);

    // Assert
    expect($ticket->title)->toBe('Updated Ticket Title');
    expect($ticket->description)->toBe('Updated ticket description');
    expect($ticket->priority)->toBe(TicketPriority::High);
    expect($ticket->status)->toBe(TicketStatus::InProgress);
    expect($ticket->due_date->isFuture())->toBeTrue();
});

it('can delete a ticket', function () {
    // Arrange
    $ticket = Ticket::factory()->create();

    // Act
    $ticket->delete();

    // Assert
    expect(Ticket::find($ticket->id))->toBeNull();
});

it('belongs to a device', function () {
    $device = Device::factory()->create();
    $ticket = Ticket::factory()->forDevice($device)->create();

    expect($ticket->device)->toBeInstanceOf(Device::class);
    expect($ticket->device->id)->toBe($device->id);
});

it('belongs to a customer via device', function () {
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();
    $ticket = Ticket::factory()->forDevice($device)->create();

    expect($ticket->customer)->toBeInstanceOf(Customer::class);
    expect($ticket->customer->id)->toBe($customer->id);
});

it('can have many tasks', function () {
    $ticket = Ticket::factory()->hasTasks(2)->create();

    expect($ticket->tasks)->toHaveCount(2);
});

it('can assign a ticket to a user', function () {
    // Arrange
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create();

    // Act
    $ticket->assignTo($user);

    // Assert
    expect($ticket->assignee_id)->toBe($user->id);
    expect($ticket->assignee)->toBeInstanceOf(User::class);
});

it('can unassign a ticket from a user', function () {
    // Arrange
    $user = User::factory()->create();
    $ticket = Ticket::factory()->forAssignee($user)->create();

    // Act
    $ticket->unassign();

    // Assert
    expect($ticket->assignee_id)->toBeNull();
    expect($ticket->assignee)->toBeNull();
});

it('can determine if a ticket is assignable', function () {
    // Arrange
    $ticket = Ticket::factory()->create();

    // Act
    $isAssignable = $ticket->isAssignable();

    // Assert
    expect($isAssignable)->toBeTrue();
});

it('can filter tickets by assignable scope', function () {
    // Arrange
    $user = User::factory()->create();
    Ticket::factory()->forAssignee($user)->create();
    Ticket::factory()->create();

    // Act
    $tickets = Ticket::assignable()->get();

    // Assert
    expect($tickets->count())->toBe(1);
    expect($tickets->first()->assignee_id)->toBeNull();
});

it('can filter tickets by priority scope', function (TicketPriority $priority) {
    // Arrange
    Ticket::factory()->ofPriority($priority)->create();

    // Act
    $tickets = Ticket::ofPriority($priority)->get();

    // Assert
    expect($tickets->count())->toBe(1);
    expect($tickets->first()->priority)->toBe($priority);
})->with(TicketPriority::cases());

it('can filter tickets by status scope', function (TicketStatus $status) {
    // Arrange
    Ticket::factory()->ofStatus($status)->create();

    // Act
    $tickets = Ticket::ofStatus($status)->get();

    // Assert
    expect($tickets->count())->toBe(1);
    expect($tickets->first()->status)->toBe($status);
})->with(TicketStatus::cases());

it('can determine if a ticket is overdue', function () {
    // Arrange
    $ticket = Ticket::factory()->overdue()->create();

    // Assert
    expect($ticket->isOverdue())->toBeTrue();
    expect($ticket->due_date->isPast())->toBeTrue();
    expect($ticket->status)->not->toBe(TicketStatus::Closed);
});

it('can filter tickets by overdue scope', function () {
    // Arrange
    Ticket::factory()->create();
    Ticket::factory()->overdue()->create();

    // Act
    $tickets = Ticket::overdue()->get();

    // Assert
    expect($tickets->count())->toBe(1);
    expect($tickets->first()->due_date->isPast())->toBeTrue();
});
