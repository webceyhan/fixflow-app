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

it('can filter records by pending scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory(2)->pending()->create();
    $modelClass::factory(1)->complete()->create();

    // Act
    $pendingModels = $modelClass::query()->pending()->get();

    // Assert
    expect($pendingModels)->toHaveCount(2);
    expect($pendingModels->first()->isPending())->toBeTrue();
})->with('models');

it('can filter records by complete scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory(2)->complete()->create();
    $modelClass::factory(1)->pending()->create();

    // Act
    $completeModels = $modelClass::query()->complete()->get();

    // Assert
    expect($completeModels)->toHaveCount(2);
    expect($completeModels->first()->isComplete())->toBeTrue();
})->with('models');
