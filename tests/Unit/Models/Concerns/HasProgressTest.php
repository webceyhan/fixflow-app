<?php

use App\Models\Device;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Task;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('models', [
    'Device' => [Device::class],
    'Ticket' => [Ticket::class],
    'Task' => [Task::class],
    'Order' => [Order::class],
    'Invoice' => [Invoice::class],
]);

it('can determine if model is pending', function (string $modelClass) {
    // Arrange
    $model = $modelClass::factory()->pending()->create();

    // Assert
    expect($model->isPending())->toBeTrue();
})->with('models');

it('can determine if model is complete', function (string $modelClass) {
    // Arrange
    $model = $modelClass::factory()->complete()->create();

    // Assert
    expect($model->isComplete())->toBeTrue();
})->with('models');

it('can filter by pending scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory()->pending()->create();
    $modelClass::factory()->complete()->create();

    // Act
    $pendingModels = $modelClass::query()->pending()->get();

    // Assert
    expect($pendingModels)->toHaveCount(1);
    expect($pendingModels->first()->isPending())->toBeTrue();
})->with('models');

it('can filter by complete scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory()->complete()->create();
    $modelClass::factory()->pending()->create();

    // Act
    $completeModels = $modelClass::query()->complete()->get();

    // Assert
    expect($completeModels)->toHaveCount(1);
    expect($completeModels->first()->isComplete())->toBeTrue();
})->with('models');
