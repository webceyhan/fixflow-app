<?php

use App\Enums\Priority;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('models', [
    'Ticket' => [Ticket::class, Priority::Medium],
]);

it('initializes model properties correctly', function (string $modelClass, BackedEnum $priority) {
    // Assert
    expect($modelClass)->toCastAttributes([
        'priority' => $priority::class,
    ]);
})->with('models');

it('can filter models by priority scope', function (string $modelClass, BackedEnum $priority) {
    // Arrange
    $modelClass::factory()->ofPriority($priority)->create();
    /** @disregard next() comes with HasNext trait */
    $modelClass::factory()->ofPriority($priority->next())->create();

    // Act
    $results = $modelClass::query()->ofPriority($priority)->get();

    // Assert
    expect($results)->toHaveCount(1);
    expect($results->first()->priority)->toBe($priority);
})->with('models');
