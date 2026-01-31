<?php

use App\Enums\CustomerType;
use App\Enums\DeviceType;
use App\Enums\TaskType;
use App\Enums\TransactionType;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Task;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('models', [
    'Customer' => [Customer::class, CustomerType::Individual],
    'Device' => [Device::class, DeviceType::Other],
    'Task' => [Task::class, TaskType::Repair],
    'Transaction' => [Transaction::class, TransactionType::Payment],
]);

it('initializes model properties correctly', function (string $modelClass, BackedEnum $type) {
    // Assert
    expect($modelClass)->toCastAttributes([
        'type' => $type::class,
    ]);
})->with('models');

it('can filter models by type scope', function (string $modelClass, BackedEnum $type) {
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
