<?php

use App\Enums\CustomerStatus;
use App\Enums\DeviceStatus;
use App\Enums\UserStatus;
use App\Models\Customer;
use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('models', [
    'User' => [User::class, UserStatus::Active],
    'Customer' => [Customer::class, CustomerStatus::Active],
    'Device' => [Device::class, DeviceStatus::Received],
]);

it('initializes model properties correctly', function (string $modelClass, BackedEnum $status) {
    // Assert
    expect($modelClass)->toCastAttributes([
        'status' => $status::class,
    ]);
})->with('models');

it('can filter models by status scope', function (string $modelClass, BackedEnum $status) {
    // Arrange
    $modelClass::factory()->ofStatus($status)->create();
    /** @disregard next() comes with HasNext trait */
    $modelClass::factory()->ofStatus($status->next())->create();

    // Act
    $results = $modelClass::query()->ofStatus($status)->get();

    // Assert
    expect($results)->toHaveCount(1);
    expect($results->first()->status)->toBe($status);
})->with('models');
