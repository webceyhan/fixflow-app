<?php

use App\Models\Customer;
use App\Models\Device;
use App\Observers\DeviceObserver;

beforeEach(function () {
    $this->observer = new DeviceObserver;
});

it('updates customer device-counts on creation', function () {
    // Arrange
    $customer = mock(Customer::class);
    $device = mock(Device::class);

    $device->shouldReceive('load')
        ->once()
        ->with('customer')
        ->andReturnSelf();

    $device->shouldReceive('getAttribute')
        ->with('customer')
        ->once()
        ->andReturn($customer);

    $customer->shouldReceive('fillDeviceCounts')
        ->once()
        ->andReturnSelf();

    $customer->shouldReceive('save')
        ->once()
        ->andReturn(true);

    // Act
    $this->observer->created($device);
});

it('does nothing when status was not changed', function () {
    // Arrange
    $device = mock(Device::class);

    $device->shouldReceive('wasChanged')
        ->once()
        ->with(['status'])
        ->andReturn(false);

    // device should not be loaded or modified when no relevant changes
    $device->shouldNotReceive('load');

    // Act
    $this->observer->updated($device);
});

it('updates customer device-counts when status was changed', function () {
    // Arrange
    $customer = mock(Customer::class);
    $device = mock(Device::class);

    $device->shouldReceive('wasChanged')
        ->once()
        ->with(['status'])
        ->andReturn(true);

    $device->shouldReceive('load')
        ->once()
        ->with('customer')
        ->andReturnSelf();

    $device->shouldReceive('getAttribute')
        ->with('customer')
        ->once()
        ->andReturn($customer);

    $customer->shouldReceive('fillDeviceCounts')
        ->once()
        ->andReturnSelf();

    $customer->shouldReceive('save')
        ->once()
        ->andReturn(true);

    $this->observer->updated($device);
});

it('updates customer device-counts on deletion', function () {
    // Arrange
    $customer = mock(Customer::class);
    $device = mock(Device::class);

    $device->shouldReceive('load')
        ->once()
        ->with('customer')
        ->andReturnSelf();

    $device->shouldReceive('getAttribute')
        ->with('customer')
        ->once()
        ->andReturn($customer);

    $customer->shouldReceive('fillDeviceCounts')
        ->once()
        ->andReturnSelf();

    $customer->shouldReceive('save')
        ->once()
        ->andReturn(true);

    // Act
    $this->observer->deleted($device);
});
