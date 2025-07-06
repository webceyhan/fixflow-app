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
    'User' => [User::class, UserStatus::Active, UserStatus::Terminated],
    'Device' => [Device::class, DeviceStatus::Received, DeviceStatus::OnHold],
    'Ticket' => [Ticket::class, TicketStatus::New, TicketStatus::InProgress],
    'Task' => [Task::class, TaskStatus::New, TaskStatus::Cancelled],
    'Order' => [Order::class, OrderStatus::New, OrderStatus::Cancelled],
    'Invoice' => [Invoice::class, InvoiceStatus::Draft, InvoiceStatus::Sent],
]);

it('can filter records by status scope', function (string $modelClass, BackedEnum $status, BackedEnum $otherStatus) {
    // Arrange
    $modelClass::factory(2)->ofStatus($status)->create();
    $modelClass::factory(1)->ofStatus($otherStatus)->create();

    // Act
    $results = $modelClass::query()->ofStatus($status)->get();

    // Assert
    expect($results)->toHaveCount(2);
    expect($results->first()->status)->toBe($status);
})->with('models');
