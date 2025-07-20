<?php

use App\Enums\OrderStatus;
use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Order;
use App\Models\Task;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a ticket with valid attributes', function () {
    // Arrange
    $ticket = Ticket::factory()->create();

    // Assert
    expect($ticket->device_id)->not->toBeNull();
    expect($ticket->title)->not->toBeEmpty();
    expect($ticket->description)->not->toBeEmpty();
    expect($ticket->priority)->toBeInstanceOf(Priority::class);
    expect($ticket->status)->toBeInstanceOf(TicketStatus::class);
    expect($ticket->due_date)->not->toBeNull();
});

it('can update a ticket', function () {
    // Arrange
    $ticket = Ticket::factory()->create();

    // Act
    $ticket->update([
        'title' => 'Updated Ticket Title',
        'description' => 'Updated ticket description',
        'priority' => Priority::High,
        'status' => TicketStatus::InProgress,
        'due_date' => now()->addMonth(),
    ]);

    // Assert
    expect($ticket->title)->toBe('Updated Ticket Title');
    expect($ticket->description)->toBe('Updated ticket description');
    expect($ticket->priority)->toBe(Priority::High);
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

it('can have many orders', function () {
    $ticket = Ticket::factory()->hasOrders(2)->create();

    expect($ticket->orders)->toHaveCount(2);
});

// TASK COUNTS /////////////////////////////////////////////////////////////////////////////////////

it('fills task-counts correctly with no tasks', function () {
    // Arrange
    $ticket = Ticket::factory()->create();

    // Assert
    expect($ticket->fillTaskCounts())
        ->pending_tasks_count->toBe(0)
        ->complete_tasks_count->toBe(0)
        ->total_tasks_count->toBe(0);
});

it('fills task-counts correctly', function (TaskStatus $status, int $pendingCount, int $completeCount, int $totalCount) {
    // Arrange
    $ticket = Ticket::factory()->create();
    Task::factory()->forTicket($ticket)->pending()->create();
    Task::factory()->forTicket($ticket)->complete()->create();
    Task::factory()->forTicket($ticket)->ofStatus($status)->create();

    // Assert
    expect($ticket->fillTaskCounts())
        ->pending_tasks_count->toBe($pendingCount)
        ->complete_tasks_count->toBe($completeCount)
        ->total_tasks_count->toBe($totalCount);
})->with([
    'new' => [TaskStatus::New, 2, 1, 3],
    'completed' => [TaskStatus::Completed, 1, 2, 3],
    'cancelled' => [TaskStatus::Cancelled, 1, 1, 3],
]);

// ORDER COUNTS ////////////////////////////////////////////////////////////////////////////////

it('fills order-counts correctly with no orders', function () {
    // Arrange
    $ticket = Ticket::factory()->create();

    // Assert
    expect($ticket->fillOrderCounts())
        ->pending_orders_count->toBe(0)
        ->complete_orders_count->toBe(0)
        ->total_orders_count->toBe(0);
});

it('fills order-counts correctly', function (OrderStatus $status, int $pendingCount, int $completeCount, int $totalCount) {
    // Arrange
    $ticket = Ticket::factory()->create();
    Order::factory()->forTicket($ticket)->pending()->create();
    Order::factory()->forTicket($ticket)->complete()->create();
    Order::factory()->forTicket($ticket)->ofStatus($status)->create();

    // Assert
    expect($ticket->fillOrderCounts())
        ->pending_orders_count->toBe($pendingCount)
        ->complete_orders_count->toBe($completeCount)
        ->total_orders_count->toBe($totalCount);
})->with([
    'new' => [OrderStatus::New, 2, 1, 3],
    'shipped' => [OrderStatus::Shipped, 2, 1, 3],
    'received' => [OrderStatus::Received, 1, 2, 3],
    'cancelled' => [OrderStatus::Cancelled, 1, 1, 3],
]);
