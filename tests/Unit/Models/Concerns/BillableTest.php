<?php

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('billableModels', [
    'Task' => [Task::class],
]);

it('can determine if model is billable', function (string $modelClass) {
    // Arrange
    $billableModel = $modelClass::factory()->create(['is_billable' => true]);
    $notBillableModel = $modelClass::factory()->create(['is_billable' => false]);

    // Assert
    expect($billableModel->isBillable())->toBeTrue();
    expect($notBillableModel->isBillable())->toBeFalse();
})->with('billableModels');

it('can filter billable models scope', function (string $modelClass) {
    // Arrange
    $billableModel = $modelClass::factory()->create(['is_billable' => true]);
    $notBillableModel = $modelClass::factory()->create(['is_billable' => false]);

    // Act
    $models = $modelClass::billable()->get();

    // Assert
    expect($models)->toHaveCount(1);
    expect($models->first()->id)->toBe($billableModel->id);
})->with('billableModels');

it('can filter not billable models scope', function (string $modelClass) {
    // Arrange
    $billableModel = $modelClass::factory()->create(['is_billable' => true]);
    $notBillableModel = $modelClass::factory()->create(['is_billable' => false]);

    // Act
    $models = $modelClass::notBillable()->get();

    // Assert
    expect($models)->toHaveCount(1);
    expect($models->first()->id)->toBe($notBillableModel->id);
})->with('billableModels');
