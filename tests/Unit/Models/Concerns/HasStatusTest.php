<?php

use App\Enums\DeviceStatus;
use App\Enums\InvoiceStatus;
use App\Enums\OrderStatus;
use App\Enums\TaskStatus;
use App\Enums\TicketStatus;
use App\Enums\UserStatus;
use App\Models\Device;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('models', [
    'User' => [User::class, UserStatus::Active],
    'Device' => [Device::class, DeviceStatus::Received],
    'Ticket' => [Ticket::class, TicketStatus::New],
    'Task' => [Task::class, TaskStatus::New],
    'Order' => [Order::class, OrderStatus::New],
    'Invoice' => [Invoice::class, InvoiceStatus::Draft],
]);

it('initializes model properties correctly', function (string $modelClass, BackedEnum $status) {
    // Assert
    expect($modelClass)->toCastAttributes([
        'status' => $status::class,
    ]);
})->with('models');

it('can filter by status scope', function (string $modelClass, BackedEnum $status) {
    // Arrange
    $modelClass::factory()->ofStatus($status)->create();
    /** @disregard next() comes with HasNext trait */
    $modelClass::factory()->ofStatus($status->next())->create();

    // Act
    $results = $modelClass::query()->ofStatus($status)->get();

    // Assert
    expect($results)->toHaveCount(1);
    expect($results->first()->status)->toBe($status);
})->with('models');
