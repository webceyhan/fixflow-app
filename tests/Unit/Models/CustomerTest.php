<?php

use App\Enums\CustomerStatus;
use App\Enums\CustomerType;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasStatus;
use App\Models\Concerns\HasType;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Customer::class,
    concerns: [
        HasActiveStatus::class,
        HasStatus::class,
        HasType::class,
    ],
    defaults: [
        'type' => CustomerType::Individual,
        'status' => CustomerStatus::Active,
    ],
    fillables: [
        'name',
        'company',
        'tax_number',
        'email',
        'phone',
        'address',
        'note',
        'type',
        'status',
    ],
    hiddens: [
        // No hidden attributes
    ],
    casts: [
        'type' => CustomerType::class,
        'status' => CustomerStatus::class,
    ],
    relations: [
        'devices' => HasMany::class,
        'tickets' => HasManyThrough::class,
    ]
);

// RELATION TESTS //////////////////////////////////////////////////////////////////////////////////

it('has many devices relationship', function () {
    // Arrange
    $customer = Customer::factory()->create();
    Device::factory()->count(2)->forCustomer($customer)->create();

    // Assert
    expect($customer->devices)->toHaveCount(2);
    expect($customer->devices->first())->toBeInstanceOf(Device::class);
    expect($customer->devices->first()->customer_id)->toBe($customer->id);
});

it('has many tickets through devices relationship', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $device1 = Device::factory()->forCustomer($customer)->create();
    $device2 = Device::factory()->forCustomer($customer)->create();
    Ticket::factory()->forDevice($device1)->create();
    Ticket::factory()->forDevice($device2)->create();

    $otherCustomer = Customer::factory()->create();
    $otherDevice = Device::factory()->forCustomer($otherCustomer)->create();
    Ticket::factory()->forDevice($otherDevice)->create(); // Not this customer's ticket

    // Assert
    expect($customer->tickets)->toHaveCount(2);
    expect($customer->tickets->first())->toBeInstanceOf(Ticket::class);
});

// SCOPE TESTS /////////////////////////////////////////////////////////////////////////////////////

// METHOD TESTS ///////////////////////////////////////////////////////////////////////////////////
