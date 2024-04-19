<?php

use App\Models\Device;
use Illuminate\Support\Carbon;

it('can determine if device has warranty', function () {
    $device = Device::factory()->withWarranty()->create();

    expect($device->hasWarranty())->toBeTrue();
    expect($device->warranty_expire_date)->toBeInstanceOf(Carbon::class);
});

it('can determine if device has no warranty', function () {
    $device = Device::factory()->create();

    expect($device->hasWarranty())->toBeFalse();
    expect($device->warranty_expire_date)->toBeNull();
});

it('can determine if device warranty has expired', function () {
    $device = Device::factory()->withWarranty(-1)->create();

    expect($device->hasWarranty())->toBeFalse();
    expect($device->warranty_expire_date)->toBeInstanceOf(Carbon::class);
});

it('can filter devices by warranty scope', function () {
    Device::factory()->create();
    Device::factory()->withWarranty()->create();

    expect(Device::withWarranty()->count())->toBe(1);
    expect(Device::withWarranty()->first()->hasWarranty())->toBeTrue();
});
