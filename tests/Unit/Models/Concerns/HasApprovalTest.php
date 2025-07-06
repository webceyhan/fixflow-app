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
    expect($model->getCasts())->toHaveKey('approved_at', 'datetime');
})->with('models');

it('can determine if model is approved', function (string $modelClass) {
    // Arrange
    $model = $modelClass::factory()->approved()->create();

    // Assert
    expect($model->isApproved())->toBeTrue();
    expect($model->approved_at)->not->toBeNull();
})->with('models');

it('can filter records by approved scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory(2)->approved()->create();
    $modelClass::factory(1)->unapproved()->create();

    // Act
    $approvedModels = $modelClass::query()->approved()->get();

    // Assert
    expect($approvedModels)->toHaveCount(2);
    expect($approvedModels->first()->isApproved())->toBeTrue();
})->with('models');

it('can filter records by unapproved scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory(2)->unapproved()->create();
    $modelClass::factory(1)->approved()->create();

    // Act
    $unapprovedModels = $modelClass::query()->unapproved()->get();

    // Assert
    expect($unapprovedModels)->toHaveCount(2);
    expect($unapprovedModels->first()->isApproved())->toBeFalse();
})->with('models');
