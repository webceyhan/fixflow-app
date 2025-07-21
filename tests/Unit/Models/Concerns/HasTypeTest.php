<?php

use App\Enums\AdjustmentType;
use App\Enums\DeviceType;
use App\Enums\TaskType;
use App\Enums\TransactionType;
use App\Models\Adjustment;
use App\Models\Device;
use App\Models\Task;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('models', [
    'Device' => [Device::class, DeviceType::Phone],
    'Task' => [Task::class, TaskType::Repair],
    'Adjustment' => [Adjustment::class, AdjustmentType::Discount],
    'Transaction' => [Transaction::class, TransactionType::Payment],
]);

it('initializes model properties correctly', function (string $modelClass, BackedEnum $type) {
    // Assert
    expect($modelClass)->toCastAttributes([
        'type' => $type::class,
    ]);
})->with('models');

it('can filter by type scope', function (string $modelClass, BackedEnum $type) {
    // Arrange
    $modelClass::factory()->ofType($type)->create();
    /** @disregard next() comes with HasNext trait */
    $modelClass::factory()->ofType($type->next())->create();

    // Act
    $results = $modelClass::query()->ofType($type)->get();

    // Assert
    expect($results)->toHaveCount(1);
    expect($results->first()->type)->toBe($type);
})->with('models');
