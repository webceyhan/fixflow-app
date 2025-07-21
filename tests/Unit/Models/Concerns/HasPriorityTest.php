<?php

use App\Enums\Priority;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('models', [
    'Ticket' => [Ticket::class],
]);

it('initializes model properties correctly', function (string $modelClass) {
    // Assert
    expect($modelClass)->toHaveDefaultAttributes([
        'priority' => Priority::Medium,
    ]);

    expect($modelClass)->toCastAttributes([
        'priority' => Priority::class,
    ]);
})->with('models');

it('can determine if model is urgent', function (string $modelClass) {
    // Arrange
    $urgentModel = $modelClass::factory()->urgent()->create();
    $nonUrgentModel = $modelClass::factory()->lowPriority()->create();

    // Assert
    expect($urgentModel->isUrgent())->toBeTrue();
    expect($urgentModel->priority)->toBe(Priority::Urgent);
    expect($nonUrgentModel->isUrgent())->toBeFalse();
    expect($nonUrgentModel->priority)->toBe(Priority::Low);
})->with('models');

it('can filter by priority scope', function (string $modelClass, Priority $priority) {
    // Arrange
    $modelClass::factory()->ofPriority($priority)->create();

    // Act
    $models = $modelClass::query()->ofPriority($priority)->get();

    // Assert
    expect($models)->toHaveCount(1);
    expect($models->first()->priority)->toBe($priority);
})->with('models')->with(Priority::cases());

it('can filter by urgent scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory(2)->urgent()->create();
    $modelClass::factory(1)->mediumPriority()->create();

    // Act
    $models = $modelClass::query()->urgent()->get();

    // Assert
    expect($models)->toHaveCount(2);
    expect($models->first()->isUrgent())->toBeTrue();
})->with('models');

it('can order records by priority', function (string $modelClass) {
    // Arrange
    $modelClass::factory()->mediumPriority()->create();
    $modelClass::factory()->lowPriority()->create();
    $modelClass::factory()->urgent()->create();
    $modelClass::factory()->highPriority()->create();

    // Act
    $models = $modelClass::query()->prioritized()->get();

    // Assert
    expect($models)->toHaveCount(4);
    expect($models->first()->priority)->toBe(Priority::Urgent);
    expect($models->last()->priority)->toBe(Priority::Low);
})->with('models');
