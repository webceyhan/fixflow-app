<?php

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('models', [
    'Ticket' => [Ticket::class],
]);

it('can determine if model is assignable', function (string $modelClass) {
    // Arrange
    $model = $modelClass::factory()->assigned()->create();

    // Assert
    expect($model->isAssigned())->toBeTrue();
    expect($model->assignee_id)->not->toBeNull();
})->with('models');

it('has an assignee relation', function (string $modelClass) {
    // Arrange
    $user = User::factory()->create();
    $model = $modelClass::factory()->forAssignee($user)->create();

    // Assert
    expect($model->assignee)->toBeInstanceOf(User::class);
    expect($model->assignee->id)->toBe($user->id);
})->with('models');

it('can assign a model to a user', function (string $modelClass) {
    // Arrange
    $user = User::factory()->create();
    $model = $modelClass::factory()->unassigned()->create();

    // Act
    $model->assignTo($user);

    // Assert
    expect($model->assignee)->toBeInstanceOf(User::class);
    expect($model->assignee->id)->toBe($user->id);
})->with('models');

it('can unassign a model from a user', function (string $modelClass) {
    // Arrange
    $model = $modelClass::factory()->assigned()->create();

    // Act
    $model->unassign();

    // Assert
    expect($model->assignee)->toBeNull();
})->with('models');

it('can filter records by assigned scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory(2)->assigned()->create();
    $modelClass::factory(1)->unassigned()->create();

    // Act
    $assignedModels = $modelClass::query()->assigned()->get();

    // Assert
    expect($assignedModels)->toHaveCount(2);
    expect($assignedModels->first()->isAssigned())->toBeTrue();
})->with('models');

it('can filter records by unassigned scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory(2)->unassigned()->create();
    $modelClass::factory(1)->assigned()->create();

    // Act
    $unassignedModels = $modelClass::query()->unassigned()->get();

    // Assert
    expect($unassignedModels)->toHaveCount(2);
    expect($unassignedModels->first()->isAssigned())->toBeFalse();
})->with('models');
