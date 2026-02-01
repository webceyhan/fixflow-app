<?php

use App\Enums\InvoiceStatus;
use App\Enums\OrderStatus;
use App\Enums\TaskStatus;
use App\Enums\TicketStatus;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Task;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('hasCancelledStatusModels', [
    'Ticket' => [Ticket::class, TicketStatus::class],
    'Task' => [Task::class, TaskStatus::class],
    'Order' => [Order::class, OrderStatus::class],
    'Invoice' => [Invoice::class, InvoiceStatus::class],
]);

it('can determine if model is cancelled', function (string $modelClass, string $enumClass) {
    // Arrange
    $cancelledModel = $modelClass::factory()->cancelled()->create();
    $notCancelledModel = $modelClass::factory()->create();

    // Assert
    expect($cancelledModel->isCancelled())->toBeTrue();
    expect($notCancelledModel->isCancelled())->toBeFalse();
})->with('hasCancelledStatusModels');

it('can filter models by cancelled scope', function (string $modelClass, string $enumClass) {
    // Arrange
    $cancelledModel = $modelClass::factory()->cancelled()->create();
    $notCancelledModel = $modelClass::factory()->create();

    // Act
    $models = $modelClass::cancelled()->get();

    // Assert
    expect($models)->toHaveCount(1);
    expect($models->first()->id)->toBe($cancelledModel->id);
})->with('hasCancelledStatusModels');

it('can filter models by not-cancelled scope', function (string $modelClass, string $enumClass) {
    // Arrange
    $cancelledModel = $modelClass::factory()->cancelled()->create();
    $notCancelledModel = $modelClass::factory()->create();

    // Act
    $models = $modelClass::notCancelled()->get();

    // Assert
    expect($models)->toHaveCount(1);
    expect($models->first()->id)->toBe($notCancelledModel->id);
})->with('hasCancelledStatusModels');
