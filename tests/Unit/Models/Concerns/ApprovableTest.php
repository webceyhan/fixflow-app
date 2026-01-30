<?php

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('approvableModels', [
    'Task' => [Task::class],
]);

it('can determine if model is approved', function (string $modelClass) {
    // Arrange
    $approvedModel = $modelClass::factory()->create(['approved_at' => now()]);
    $unapprovedModel = $modelClass::factory()->create(['approved_at' => null]);

    // Assert
    expect($approvedModel->isApproved())->toBeTrue();
    expect($unapprovedModel->isApproved())->toBeFalse();
})->with('approvableModels');

it('can filter approved models scope', function (string $modelClass) {
    // Arrange
    $approvedModel = $modelClass::factory()->create(['approved_at' => now()]);
    $unapprovedModel = $modelClass::factory()->create(['approved_at' => null]);

    // Act
    $models = $modelClass::approved()->get();

    // Assert
    expect($models)->toHaveCount(1);
    expect($models->first()->id)->toBe($approvedModel->id);
})->with('approvableModels');

it('can filter unapproved models scope', function (string $modelClass) {
    // Arrange
    $approvedModel = $modelClass::factory()->create(['approved_at' => now()]);
    $unapprovedModel = $modelClass::factory()->create(['approved_at' => null]);

    // Act
    $models = $modelClass::unapproved()->get();

    // Assert
    expect($models)->toHaveCount(1);
    expect($models->first()->id)->toBe($unapprovedModel->id);
})->with('approvableModels');
