<?php

use App\Enums\DeviceStatus;
use App\Models\Device;

it('can determine if device is completed', function (DeviceStatus $status) {
    $device = Device::factory()->ofStatus($status)->create();

    expect($device->isCompleted())->toBeTrue();
})->with(DeviceStatus::completedCases());

describe('scopes', function () {
    beforeEach(function () {
        collect(DeviceStatus::cases())->each(function (DeviceStatus $status) {
            Device::factory()->ofStatus($status)->create();
        });
    });

    it('can filter devices by pending scope', function () {
        expect(Device::pending()->count())->toBe(3);
        expect(Device::pending()->first()->isCompleted())->toBeFalse();
    });

    it('can filter devices by completed scope', function () {
        expect(Device::completed()->count())->toBe(2);
        expect(Device::completed()->first()->isCompleted())->toBeTrue();
    });
});
