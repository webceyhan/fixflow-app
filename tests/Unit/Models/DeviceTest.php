<?php

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasStatus;
use App\Models\Concerns\HasType;
use App\Models\Concerns\HasWarranty;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Device::class,
    concerns: [
        HasActiveStatus::class,
        HasStatus::class,
        HasType::class,
        HasWarranty::class,
    ],
    defaults: [
        'type' => DeviceType::Other,
        'status' => DeviceStatus::Received,
    ],
    fillables: [
        'customer_id',
        'model',
        'brand',
        'color',
        'imei',
        'serial_number',
        'passcode',
        'purchase_date',
        'warranty_expire_date',
        'note',
        'type',
        'status',
    ],
    hiddens: [
        // No hidden attributes
    ],
    casts: [
        'passcode' => 'encrypted',
        'warranty_expire_date' => 'date',
        'type' => DeviceType::class,
        'status' => DeviceStatus::class,
    ],
    relations: [
        'customer' => BelongsTo::class,
        'tickets' => HasMany::class,
    ]
);

// RELATION TESTS //////////////////////////////////////////////////////////////////////////////////

it('belongs to customer relationship', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();

    // Assert
    expect($device->customer)->toBeInstanceOf(Customer::class);
    expect($device->customer->id)->toBe($customer->id);
});

it('has many tickets relationship', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();
    Ticket::factory()->count(3)->forDevice($device)->create();

    $otherDevice = Device::factory()->forCustomer($customer)->create();
    Ticket::factory()->forDevice($otherDevice)->create(); // Not this device's ticket

    // Assert
    expect($device->tickets)->toHaveCount(3);
    expect($device->tickets->first())->toBeInstanceOf(Ticket::class);
    expect($device->tickets->first()->device_id)->toBe($device->id);
});

// SCOPE TESTS /////////////////////////////////////////////////////////////////////////////////////

// METHOD TESTS ////////////////////////////////////////////////////////////////////////////////////
