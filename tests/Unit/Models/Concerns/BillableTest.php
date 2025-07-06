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
    // Arrange
    $model = new $modelClass;

    // Assert
    expect($model->getCasts())->toHaveKey('is_billable', 'boolean');
    expect($model->getAttributes())->toHaveKey('is_billable', true);
})->with('models');

it('can determine if model is billable', function (string $modelClass) {
    // Arrange
    $model = $modelClass::factory()->billable()->create();

    // Assert
    expect($model->isBillable())->toBeTrue();
    expect($model->is_billable)->toBeTrue();
})->with('models');

it('can filter records by billable scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory(2)->billable()->create();
    $modelClass::factory(1)->notBillable()->create();

    // Act
    $billableModels = $modelClass::query()->billable()->get();

    // Assert
    expect($billableModels)->toHaveCount(2);
    expect($billableModels->first()->isBillable())->toBeTrue();
})->with('models');

it('can filter records by not billable scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory(2)->notBillable()->create();
    $modelClass::factory(1)->billable()->create();

    // Act
    $notBillableModels = $modelClass::query()->notBillable()->get();

    // Assert
    expect($notBillableModels)->toHaveCount(2);
    expect($notBillableModels->first()->isBillable())->toBeFalse();
})->with('models');
