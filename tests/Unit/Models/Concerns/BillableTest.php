<?php

use App\Models\Order;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('models', [
    'Order' => [Order::class],
    'Task' => [Task::class],
]);

it('initializes model properties correctly', function (string $modelClass) {
    // Assert
    expect($modelClass)->toHaveDefaultAttributes([
        'is_billable' => true,
    ]);

    expect($modelClass)->toCastAttributes([
        'is_billable' => 'boolean',
    ]);
})->with('models');

it('can determine if model is billable', function (string $modelClass) {
    // Arrange
    $billableModel = $modelClass::factory()->billable()->create();
    $notBillableModel = $modelClass::factory()->notBillable()->create();

    // Assert
    expect($billableModel->isBillable())->toBeTrue();
    expect($billableModel->is_billable)->toBeTrue();

    expect($notBillableModel->isBillable())->toBeFalse();
    expect($notBillableModel->is_billable)->toBeFalse();
})->with('models');

it('can filter by billable scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory()->billable()->create();
    $modelClass::factory()->notBillable()->create();

    // Act
    $billableModels = $modelClass::query()->billable()->get();

    // Assert
    expect($billableModels)->toHaveCount(1);
    expect($billableModels->first()->isBillable())->toBeTrue();
})->with('models');

it('can filter by not billable scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory()->notBillable()->create();
    $modelClass::factory()->billable()->create();

    // Act
    $notBillableModels = $modelClass::query()->notBillable()->get();

    // Assert
    expect($notBillableModels)->toHaveCount(1);
    expect($notBillableModels->first()->isBillable())->toBeFalse();
})->with('models');
