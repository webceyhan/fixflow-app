<?php

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Models\Customer;
use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a device with valid attributes', function () {
    // Arrange
    $device = Device::factory()->create();

    // Assert
    expect($device->customer_id)->not->toBeNull();
    expect($device->model)->not->toBeEmpty();
    expect($device->type)->toBeInstanceOf(DeviceType::class);
    expect($device->status)->toBeInstanceOf(DeviceStatus::class);
});

it('can create a device without a brand', function () {
    // Arrange
    $device = Device::factory()->withoutBrand()->create();

    // Assert
    expect($device->brand)->toBeNull();
});

it('can create a device without a serial number', function () {
    // Arrange
    $device = Device::factory()->withoutSerialNumber()->create();

    // Assert
    expect($device->serial_number)->toBeNull();
});

it('can create a device without a purchase date', function () {
    // Arrange
    $device = Device::factory()->withoutPurchaseDate()->create();

    // Assert
    expect($device->purchase_date)->toBeNull();
    expect($device->warranty_expire_date)->toBeNull();
});

it('can update a device', function () {
    // Arrange
    $device = Device::factory()->create();

    // Act
    $device->update([
        'model' => 'iPhone 14',
        'brand' => 'Apple',
        'serial_number' => 'SN123456789',
        'purchase_date' => now()->subMonths(6),
        'warranty_expire_date' => now()->addMonths(6),
        'type' => DeviceType::Phone,
        'status' => DeviceStatus::UnderRepair,
    ]);

    // Assert
    expect($device->model)->toBe('iPhone 14');
    expect($device->brand)->toBe('Apple');
    expect($device->serial_number)->toBe('SN123456789');
    expect($device->purchase_date->isPast())->toBeTrue();
    expect($device->warranty_expire_date->isFuture())->toBeTrue();
    expect($device->type)->toBe(DeviceType::Phone);
    expect($device->status)->toBe(DeviceStatus::UnderRepair);
});

it('can delete a device', function () {
    // Arrange
    $device = Device::factory()->create();

    // Act
    $device->delete();

    // Assert
    expect(Device::find($device->id))->toBeNull();
});

it('can have many tickets', function () {
    $device = Device::factory()->hasTickets(2)->create();

    expect($device->tickets)->toHaveCount(2);
});

it('can get the customer that owns the device', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();

    // Assert
    expect($device->customer->id)->toBe($customer->id);
});
