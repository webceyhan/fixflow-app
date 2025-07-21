<?php

use App\Enums\OrderStatus;
use App\Enums\TaskStatus;
use App\Enums\TicketStatus;
use App\Models\Concerns\Assignable;
use App\Models\Concerns\HasDueDate;
use App\Models\Concerns\HasPriority;
use App\Models\Concerns\HasProgress;
use App\Models\Concerns\HasStatus;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Order;
use App\Models\Task;
use App\Models\Ticket;
use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Ticket::class,
    concerns: [
        Assignable::class,
        HasDueDate::class,
        HasPriority::class,
        HasProgress::class,
        HasStatus::class,
    ],
    observers: [
        TicketObserver::class,
    ],
    defaults: [
        'status' => TicketStatus::New,
        'pending_tasks_count' => 0,
        'complete_tasks_count' => 0,
        'total_tasks_count' => 0,
        'pending_orders_count' => 0,
        'complete_orders_count' => 0,
        'total_orders_count' => 0,
    ],
    fillables: [
        'title',
        'description',
        'priority',
        'status',
        'due_date',
    ],
    relations: [
        'device' => BelongsTo::class,
        'customer' => BelongsTo::class,
        'tasks' => HasMany::class,
        'orders' => HasMany::class,
        'invoice' => HasOne::class,
    ]
);

// RELATION TESTS //////////////////////////////////////////////////////////////////////////////////

it('belongs to device relationship', function () {
    // Arrange
    $device = Device::factory()->create();
    $ticket = Ticket::factory()->forDevice($device)->create();

    // Assert
    expect($ticket->device)->toBeInstanceOf(Device::class);
    expect($ticket->device->id)->toBe($device->id);
});

it('belongs to customer via device relationship', function () {
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
    Task::factory()->count(2)->forTicket($ticket)->create();

    // Assert
    expect($ticket->tasks)->toHaveCount(2);
    expect($ticket->tasks->first())->toBeInstanceOf(Task::class);
    expect($ticket->tasks->first()->ticket_id)->toBe($ticket->id);
});

it('has many orders relationship', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    Order::factory()->count(2)->forTicket($ticket)->create();

    // Assert
    expect($ticket->orders)->toHaveCount(2);
    expect($ticket->orders->first())->toBeInstanceOf(Order::class);
    expect($ticket->orders->first()->ticket_id)->toBe($ticket->id);
});

// METHOD TESTS ////////////////////////////////////////////////////////////////////////////////////

it('can fill task counts', function (TaskStatus $status, int $pendingCount, int $completeCount, int $totalCount) {
    // Arrange
    $ticket = Ticket::factory()->create();
    Task::factory()->forTicket($ticket)->pending()->create();
    Task::factory()->forTicket($ticket)->complete()->create();
    Task::factory()->forTicket($ticket)->ofStatus($status)->create();

    // Act
    $ticket->fillTaskCounts();

    // Act & Assert
    expect($ticket)
        ->pending_tasks_count->toBe($pendingCount)
        ->complete_tasks_count->toBe($completeCount)
        ->total_tasks_count->toBe($totalCount);
})->with([
    'new' => [TaskStatus::New, 2, 1, 3],
    'completed' => [TaskStatus::Completed, 1, 2, 3],
    'cancelled' => [TaskStatus::Cancelled, 1, 1, 3],
]);

it('can fill order counts', function (OrderStatus $status, int $pendingCount, int $completeCount, int $totalCount) {
    // Arrange
    $ticket = Ticket::factory()->create();
    Order::factory()->forTicket($ticket)->pending()->create();
    Order::factory()->forTicket($ticket)->complete()->create();
    Order::factory()->forTicket($ticket)->ofStatus($status)->create();

    // Act
    $ticket->fillOrderCounts();

    // Act & Assert
    expect($ticket)
        ->pending_orders_count->toBe($pendingCount)
        ->complete_orders_count->toBe($completeCount)
        ->total_orders_count->toBe($totalCount);
})->with([
    'new' => [OrderStatus::New, 2, 1, 3],
    'shipped' => [OrderStatus::Shipped, 2, 1, 3],
    'received' => [OrderStatus::Received, 1, 2, 3],
    'cancelled' => [OrderStatus::Cancelled, 1, 1, 3],
]);
