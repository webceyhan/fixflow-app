<?php

use App\Models\Adjustment;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('hasRefModels', [
    'User' => [User::class, 'USR'],
    'Customer' => [Customer::class, 'CUS'],
    'Device' => [Device::class, 'DVC'],
    'Ticket' => [Ticket::class, 'TKT'],
    'Task' => [Task::class, 'TSK'],
    'Order' => [Order::class, 'ORD'],
    'Invoice' => [Invoice::class, 'INV'],
    'Transaction' => [Transaction::class, 'TXN'],
    'Adjustment' => [Adjustment::class, 'ADJ'],
]);

it('appends ref to the model', function (string $modelClass) {
    // Arrange
    $model = $modelClass::factory()->create();

    // Assert
    expect($model->getAppends())->toContain('ref');
})->with('hasRefModels');

it('formats ref as PREFIX-000000', function (string $modelClass, string $prefix) {
    // Arrange
    $model = $modelClass::factory()->create();

    // Assert
    expect($model->ref)->toBe("{$prefix}-".str_pad($model->id, 6, '0', STR_PAD_LEFT));
})->with('hasRefModels');

it('includes ref when model is serialized', function (string $modelClass, string $prefix) {
    // Arrange
    $model = $modelClass::factory()->create();

    // Assert
    expect($model->toArray())->toHaveKey('ref');
    expect($model->toArray()['ref'])->toBe("{$prefix}-".str_pad($model->id, 6, '0', STR_PAD_LEFT));
})->with('hasRefModels');
