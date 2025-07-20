<?php

use App\Casts\Progress;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Dataset for main progress calculation scenarios
dataset('progress_calculations', [
    'partial completion' => [3, 2, 40.0], // , '40% (2 complete out of 5 total),
    'full completion' => [0, 5, 100.0], // , '100% (5 complete out of 5 total),
    'no completion' => [3, 0, 0.0], // , '0% (0 complete out of 3 total),
    'zero total counts' => [0, 0, 0.0], // , '0 when no items exist (avoid division by zero),
    'decimal precision' => [2, 1, 33.33], // , '33.33% (1 complete out of 3 total),
    'large numbers' => [9999, 1, 0.01], // , '0.01% (1 complete out of 10000 total),
]);

it('calculates progress correctly', function (int $pending, int $complete, float $expected) {
    // Arrange
    $attributes = [
        'pending_count' => $pending,
        'complete_count' => $complete,
    ];

    // Act
    $progress = Progress::using('pending_count', 'complete_count');
    $getter = $progress->get;
    $result = $getter(null, $attributes);

    // Assert
    expect($result)->toBe($expected);
})->with('progress_calculations');

it('works with different field names', function () {
    // Arrange
    $attributes = [
        'open_tasks' => 1,
        'done_tasks' => 4,
    ];

    // Act
    $progress = Progress::using('open_tasks', 'done_tasks');
    $getter = $progress->get;
    $result = $getter(null, $attributes);

    // Assert - Should be 80% (4 complete out of 5 total)
    expect($result)->toBe(80.0);
});
