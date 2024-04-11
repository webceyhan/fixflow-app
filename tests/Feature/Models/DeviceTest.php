<?php

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Models\Customer;
use App\Models\Device;
use Illuminate\Support\Carbon;

it('can initialize device', function () {
    $device = new Device();

    expect($device->id)->toBeNull();
    expect($device->customer_id)->toBeNull();
    expect($device->model)->toBeNull();
    expect($device->brand)->toBeNull();
    expect($device->serial_number)->toBeNull();
    expect($device->warranty_expire_date)->toBeNull();
    expect($device->type)->toBe(DeviceType::Other);
    expect($device->status)->toBe(DeviceStatus::CheckedIn);
    expect($device->created_at)->toBeNull();
    expect($device->updated_at)->toBeNull();
});

it('can create device', function () {
    $device = Device::factory()->create();

    expect($device->id)->toBeInt();
    expect($device->customer_id)->toBeInt();
    expect($device->model)->toBeString();
    expect($device->brand)->toBeString();
    expect($device->serial_number)->toBeString();
    expect($device->warranty_expire_date)->toBeNull();
    expect($device->type)->toBe(DeviceType::Other);
    expect($device->status)->toBe(DeviceStatus::CheckedIn);
    expect($device->created_at)->toBeInstanceOf(Carbon::class);
    expect($device->updated_at)->toBeInstanceOf(Carbon::class);
});

it('can create device without brand', function () {
    $device = Device::factory()->withoutBrand()->create();

    expect($device->brand)->toBeNull();
});

it('can create device without serial number', function () {
    $device = Device::factory()->withoutSerialNumber()->create();

    expect($device->serial_number)->toBeNull();
});

it('can create device with warranty', function (int $year) {
    $device = Device::factory()->withWarranty($year)->create();

    expect($device->warranty_expire_date)->toBeInstanceOf(Carbon::class);
})->with([1, 2]);

it('can create device of type', function (DeviceType $type) {
    $device = Device::factory()->ofType($type)->create();

    expect($device->type)->toBe($type);
})->with(DeviceType::cases());

it('can create device of status', function (DeviceStatus $status) {
    $device = Device::factory()->ofStatus($status)->create();

    expect($device->status)->toBe($status);
})->with(DeviceStatus::cases());

it('can update device', function () {
    $device = Device::factory()->create();

    $device->update([
        'model' => 'iPhone 13 Pro',
        'brand' => 'Apple',
        'serial_number' => '1234567890',
        'warranty_expire_date' => '2024-04-11',
        'type' => DeviceType::Phone,
        'status' => DeviceStatus::CheckedOut,
    ]);

    expect($device->model)->toBe('iPhone 13 Pro');
    expect($device->brand)->toBe('Apple');
    expect($device->serial_number)->toBe('1234567890');
    expect($device->warranty_expire_date)->toBeInstanceOf(Carbon::class);
    expect($device->type)->toBe(DeviceType::Phone);
    expect($device->status)->toBe(DeviceStatus::CheckedOut);
});

it('can delete device', function () {
    $device = Device::factory()->create();

    $device->delete();

    expect(Device::find($device->id))->toBeNull();
});

// Customer ////////////////////////////////////////////////////////////////////////////////////////

it('belongs to a customer', function () {
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();

    expect($device->customer)->toBeInstanceOf(Customer::class);
    expect($device->customer->id)->toBe($customer->id);
});

// Warranty ////////////////////////////////////////////////////////////////////////////////////////

it('can check if device has warranty', function () {
    $device = Device::factory()->withWarranty()->create();

    expect($device->hasWarranty())->toBeTrue();
    expect($device->warranty_expire_date)->toBeInstanceOf(Carbon::class);
});

it('can filter devices by warranty scope', function () {
    Device::factory()->create();
    Device::factory()->withWarranty()->create();

    expect(Device::withWarranty()->count())->toBe(1);
    expect(Device::withWarranty()->first()->hasWarranty())->toBeTrue();
});

// Type ////////////////////////////////////////////////////////////////////////////////////////////

it('can filter devices by type scope', function (DeviceType $type) {
    Device::factory()->ofType($type)->create();

    expect(Device::ofType($type)->count())->toBe(1);
    expect(Device::ofType($type)->first()->type)->toBe($type);
})->with(DeviceType::cases());

// Status //////////////////////////////////////////////////////////////////////////////////////////

it('can filter devices by status scope', function (DeviceStatus $status) {
    Device::factory()->ofStatus($status)->create();

    expect(Device::ofStatus($status)->count())->toBe(1);
    expect(Device::ofStatus($status)->first()->status)->toBe($status);
})->with(DeviceStatus::cases());
