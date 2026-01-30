<?php

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('hasDueDateModels', [
    'Ticket' => [Ticket::class],
    'Order' => [Order::class],
]);

it('initializes model properties correctly', function (string $modelClass) {
    // Arrange & Act
    $model = $modelClass::factory()->create();

    // Assert
    expect($model->getCasts())->toHaveKey('due_date', 'date');
})->with('hasDueDateModels');

it('can determine if model is overdue', function (string $modelClass) {
    // Arrange
    $statusEnum = (new $modelClass)->status;
    $activeCases = $statusEnum::activeCases();
    $allCases = $statusEnum::cases();
    $notActiveCases = array_values(array_filter($allCases, fn ($case) => ! in_array($case, $activeCases, true)));

    $overdueModel = $modelClass::factory()->create([
        'status' => $activeCases[0],
        'due_date' => now()->subDay(),
    ]);
    $notOverdueModel = $modelClass::factory()->create([
        'status' => $activeCases[0],
        'due_date' => now()->addDay(),
    ]);
    $completedModel = $modelClass::factory()->create([
        'status' => $notActiveCases[0],
        'due_date' => now()->subDay(),
    ]);

    // Assert
    expect($overdueModel->isOverdue())->toBeTrue();
    expect($notOverdueModel->isOverdue())->toBeFalse();
    expect($completedModel->isOverdue())->toBeFalse();

    // Only test null due_date for Ticket (Invoice has NOT NULL constraint)
    if ($modelClass === Ticket::class) {
        $noDueDateModel = $modelClass::factory()->create([
            'status' => $activeCases[0],
            'due_date' => null,
        ]);
        expect($noDueDateModel->isOverdue())->toBeFalse();
    }
})->with('hasDueDateModels');

it('can filter overdue models scope', function (string $modelClass) {
    // Arrange
    $statusEnum = (new $modelClass)->status;
    $activeCases = $statusEnum::activeCases();
    $allCases = $statusEnum::cases();
    $notActiveCases = array_values(array_filter($allCases, fn ($case) => ! in_array($case, $activeCases, true)));

    $overdueModel = $modelClass::factory()->create([
        'status' => $activeCases[0],
        'due_date' => now()->subDay(),
    ]);
    $notOverdueModel = $modelClass::factory()->create([
        'status' => $activeCases[0],
        'due_date' => now()->addDay(),
    ]);
    $completedModel = $modelClass::factory()->create([
        'status' => $notActiveCases[0],
        'due_date' => now()->subDay(),
    ]);

    // Only test null due_date for Ticket (Invoice has NOT NULL constraint)
    if ($modelClass === Ticket::class) {
        $noDueDateModel = $modelClass::factory()->create([
            'status' => $activeCases[0],
            'due_date' => null,
        ]);
    }

    // Act
    $models = $modelClass::overdue()->get();

    // Assert
    expect($models)->toHaveCount(1);
    expect($models->first()->id)->toBe($overdueModel->id);
})->with('hasDueDateModels');

it('can filter not overdue models scope', function (string $modelClass) {
    // Arrange
    $statusEnum = (new $modelClass)->status;
    $activeCases = $statusEnum::activeCases();
    $notActiveCases = array_values(array_filter($statusEnum::cases(), fn ($case) => ! in_array($case, $activeCases, true)));
    $overdueModel = $modelClass::factory()->create([
        'status' => $activeCases[0],
        'due_date' => now()->subDay(),
    ]);
    $notOverdueModel = $modelClass::factory()->create([
        'status' => $activeCases[0],
        'due_date' => now()->addDay(),
    ]);

    // Expected count varies based on model (Ticket allows null, Invoice doesn't)
    $expectedCount = 1;
    if ($modelClass === Ticket::class) {
        $noDueDateModel = $modelClass::factory()->create([
            'status' => $activeCases[0],
            'due_date' => null,
        ]);
        $expectedCount = 2;
    }

    // Act
    $models = $modelClass::notOverdue()->get();

    // Assert
    expect($models)->toHaveCount($expectedCount);
    expect($models->pluck('id'))->toContain($notOverdueModel->id);
})->with('hasDueDateModels');
