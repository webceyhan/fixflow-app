<?php

use App\Models\Customer;
use App\Models\Device;
use App\Observers\DeviceObserver;

beforeEach(function () {
    $this->observer = new DeviceObserver;

    // Helpers

    $this->mockDevice = function (bool $syncCustomer = false) {
        $device = mock(Device::class);

        if ($syncCustomer) {
            $customer = mock(Customer::class);
            $customer->shouldReceive('fillDeviceCounts')->once()->andReturnSelf();
            $customer->shouldReceive('save')->once()->andReturn(true);

            $device->shouldReceive('load')->once()->with('customer')->andReturnSelf();
            $device->shouldReceive('getAttribute')->once()->with('customer')->andReturn($customer);
        }

        return $device;
    };

    $this->mockDeviceWithUpdates = function (bool $statusChanged = false) {
        $device = $this->mockDevice(syncCustomer: $statusChanged);

        $device->shouldReceive('wasChanged')
            ->once()
            ->with(['status'])
            ->andReturn($statusChanged);

        return $device;
    };
});

it('updates customer device-counts on creation', function () {
    // Arrange
    $device = $this->mockDevice(syncCustomer: true);

    // Act
    $this->observer->created($device);
});

it('does nothing when status was not changed', function () {
    // Arrange
    $device = $this->mockDeviceWithUpdates();

    // Act
    $this->observer->updated($device);
});

it('updates customer device-counts when status was changed', function () {
    // Arrange
    $device = $this->mockDeviceWithUpdates(statusChanged: true);

    $this->observer->updated($device);
});

it('updates customer device-counts on deletion', function () {
    // Arrange
    $device = $this->mockDevice(syncCustomer: true);

    // Act
    $this->observer->deleted($device);
});
