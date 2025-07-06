<?php

use App\Enums\DeviceType;
use App\Enums\TaskType;
use App\Enums\TransactionType;
use App\Models\Device;
use App\Models\Task;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('models', [
    'Device' => [Device::class, DeviceType::Phone, DeviceType::Laptop],
    'Task' => [Task::class, TaskType::Repair, TaskType::Diagnostic],
    'Transaction' => [Transaction::class, TransactionType::Payment, TransactionType::Refund],
]);

it('can filter records by type scope', function (string $modelClass, BackedEnum $type, BackedEnum $otherType) {
    // Arrange
    $modelClass::factory(2)->ofType($type)->create();
    $modelClass::factory(1)->ofType($otherType)->create();

    // Act
    $results = $modelClass::query()->ofType($type)->get();

    // Assert
    expect($results)->toHaveCount(2);
    expect($results->first()->type)->toBe($type);
})->with('models');
