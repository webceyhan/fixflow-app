<?php

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasStatus;
use App\Models\Concerns\HasType;
use App\Models\Concerns\HasWarranty;
use App\Models\Customer;
use App\Models\Device;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

// SCOPE TESTS /////////////////////////////////////////////////////////////////////////////////////

// METHOD TESTS ////////////////////////////////////////////////////////////////////////////////////
