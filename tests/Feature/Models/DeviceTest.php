<?php

use App\Models\Device;
use Illuminate\Support\Carbon;

it('can initialize device', function () {
    $device = new Device();

    expect($device->id)->toBeNull();
    expect($device->model)->toBeNull();
    expect($device->brand)->toBeNull();
    expect($device->serial_number)->toBeNull();
    expect($device->warranty_expire_date)->toBeNull();
    expect($device->created_at)->toBeNull();
    expect($device->updated_at)->toBeNull();
});

it('can create device', function () {
    $device = Device::factory()->create();

    expect($device->id)->toBeInt();
    expect($device->model)->toBeString();
    expect($device->brand)->toBeString();
    expect($device->serial_number)->toBeString();
    expect($device->warranty_expire_date)->toBeNull();
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

it('can update device', function () {
    $device = Device::factory()->create();

    $device->update([
        'model' => 'iPhone 13 Pro',
        'brand' => 'Apple',
        'serial_number' => '1234567890',
        'warranty_expire_date' => '2024-04-11',
    ]);

    expect($device->model)->toBe('iPhone 13 Pro');
    expect($device->brand)->toBe('Apple');
    expect($device->serial_number)->toBe('1234567890');
    expect($device->warranty_expire_date)->toBeInstanceOf(Carbon::class);
});

it('can delete device', function () {
    $device = Device::factory()->create();

    $device->delete();

    expect(Device::find($device->id))->toBeNull();
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
