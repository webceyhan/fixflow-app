<?php

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('assignableModels', [
    'Ticket' => [Ticket::class],
]);

it('can determine if model is assigned', function (string $modelClass) {
    // Arrange
    $user = User::factory()->create();
    $assignedModel = $modelClass::factory()->create(['assignee_id' => $user->id]);
    $unassignedModel = $modelClass::factory()->create(['assignee_id' => null]);

    // Assert
    expect($assignedModel->isAssigned())->toBeTrue();
    expect($unassignedModel->isAssigned())->toBeFalse();
})->with('assignableModels');

it('has assignee relationship', function (string $modelClass) {
    // Arrange
    $user = User::factory()->create();
    $model = $modelClass::factory()->create(['assignee_id' => $user->id]);

    // Assert
    expect($model->assignee)->toBeInstanceOf(User::class);
    expect($model->assignee->id)->toBe($user->id);
})->with('assignableModels');

it('can have null assignee', function (string $modelClass) {
    // Arrange
    $model = $modelClass::factory()->create(['assignee_id' => null]);

    // Assert
    expect($model->assignee)->toBeNull();
})->with('assignableModels');

it('can filter assigned models scope', function (string $modelClass) {
    // Arrange
    $user = User::factory()->create();
    $assignedModel = $modelClass::factory()->create(['assignee_id' => $user->id]);
    $unassignedModel = $modelClass::factory()->create(['assignee_id' => null]);

    // Act
    $models = $modelClass::assigned()->get();

    // Assert
    expect($models)->toHaveCount(1);
    expect($models->first()->id)->toBe($assignedModel->id);
})->with('assignableModels');

it('can filter unassigned models scope', function (string $modelClass) {
    // Arrange
    $user = User::factory()->create();
    $assignedModel = $modelClass::factory()->create(['assignee_id' => $user->id]);
    $unassignedModel = $modelClass::factory()->create(['assignee_id' => null]);

    // Act
    $models = $modelClass::unassigned()->get();

    // Assert
    expect($models)->toHaveCount(1);
    expect($models->first()->id)->toBe($unassignedModel->id);
})->with('assignableModels');
