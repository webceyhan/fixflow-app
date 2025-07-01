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

it('can create a device out of warranty', function () {
    // Arrange
    $device = Device::factory()->outOfWarranty()->create();

    // Assert
    expect($device->purchase_date->isPast())->toBeTrue();
    expect($device->warranty_expire_date->isPast())->toBeTrue();
});

it('can create a device of type', function (DeviceType $type) {
    // Arrange
    $device = Device::factory()->ofType($type)->create();

    // Assert
    expect($device->type)->toBe($type);
})->with(DeviceType::cases());

it('can create a device of status', function (DeviceStatus $status) {
    // Arrange
    $device = Device::factory()->ofStatus($status)->create();

    // Assert
    expect($device->status)->toBe($status);
})->with(DeviceStatus::cases());

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

it('can determine if device has warranty', function () {
    // Arrange
    $device = Device::factory()->outOfWarranty()->create();

    // Assert
    expect($device->hasWarranty())->toBeFalse();

    // Act
    $device->purchase_date = now()->subMonths(3);
    $device->warranty_expire_date = now()->addMonths(9);
    $device->save();

    // Assert
    expect($device->hasWarranty())->toBeTrue();
});

it('can get the customer that owns the device', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();

    // Assert
    expect($device->customer->id)->toBe($customer->id);
});

it('can scope devices with warranty', function () {
    // Arrange
    Device::factory()->outOfWarranty()->create();
    $deviceWithWarranty = Device::factory()->create([
        'purchase_date' => now()->subMonths(3),
        'warranty_expire_date' => now()->addMonths(9),
    ]);

    // Act
    $devicesWithWarranty = Device::withWarranty()->get();

    // Assert
    expect($devicesWithWarranty)->toHaveCount(1);
    expect($devicesWithWarranty->first()->id)->toBe($deviceWithWarranty->id);
});

it('can filter devices by type scope', function (DeviceType $type) {
    // Arrange
    Device::factory()->ofType($type)->create();

    // Act
    $devices = Device::ofType($type)->get();

    // Assert
    expect($devices)->toHaveCount(1);
    expect($devices->first()->type)->toBe($type);
})->with(DeviceType::cases());

it('can filter devices by status scope', function (DeviceStatus $status) {
    // Arrange
    Device::factory()->ofStatus($status)->create();

    // Act
    $devices = Device::ofStatus($status)->get();

    // Assert
    expect($devices)->toHaveCount(1);
    expect($devices->first()->status)->toBe($status);
})->with(DeviceStatus::cases());
