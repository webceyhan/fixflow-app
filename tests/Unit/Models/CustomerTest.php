<?php

use App\Enums\DeviceStatus;
use App\Models\Customer;
use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a customer with valid attributes', function () {
    // Arrange & Act
    $customer = Customer::factory()->create();

    // Assert
    expect($customer->name)->not->toBeEmpty();
    expect($customer->email)->not->toBeEmpty();
    expect($customer->address)->not->toBeEmpty();
});

it('can create customer without address', function () {
    // Act
    $customer = Customer::factory()->withoutAddress()->create();

    // Assert
    expect($customer->address)->toBeNull();
});

it('can create customer without note', function () {
    // Act
    $customer = Customer::factory()->withoutNote()->create();

    // Assert
    expect($customer->note)->toBeNull();
});

it('can update customer', function () {
    // Arrange
    $customer = Customer::factory()->create();

    // Act
    $customer->update([
        'name' => 'Acme Corp',
        'email' => 'contact@acme.com',
        'phone' => '+1234567890',
        'address' => '123 Main St',
        'note' => 'VIP',
    ]);

    // Assert
    expect($customer->name)->toBe('Acme Corp');
    expect($customer->email)->toBe('contact@acme.com');
    expect($customer->phone)->toBe('+1234567890');
    expect($customer->address)->toBe('123 Main St');
    expect($customer->note)->toBe('VIP');
});

it('can delete customer', function () {
    // Arrange
    $customer = Customer::factory()->create();

    // Act
    $customer->delete();

    // Assert
    expect(Customer::find($customer->id))->toBeNull();
});

it('can have many devices', function () {
    // Arrange & Act
    $customer = Customer::factory()->hasDevices(2)->create();

    // Assert
    expect($customer->devices)->toHaveCount(2);
});

it('can have many tickets via devices', function () {
    $customer = Customer::factory()->create();
    Device::factory()->count(2)->forCustomer($customer)->hasTickets(2)->create();

    expect($customer->tickets)->toHaveCount(4);
});

// DEVICE COUNTS ///////////////////////////////////////////////////////////////////////////////////

it('fills device-counts correctly with no devices', function () {
    // Arrange
    $customer = Customer::factory()->create();

    // Assert
    expect($customer->fillDeviceCounts())
        ->pending_devices_count->toBe(0)
        ->complete_devices_count->toBe(0)
        ->total_devices_count->toBe(0);
});

it('fills device-counts correctly', function (DeviceStatus $status, int $pendingCount, int $completeCount, int $totalCount) {
    // Arrange
    $customer = Customer::factory()->create();
    Device::factory()->forCustomer($customer)->pending()->create();
    Device::factory()->forCustomer($customer)->complete()->create();
    Device::factory()->forCustomer($customer)->ofStatus($status)->create();

    // Assert
    expect($customer->fillDeviceCounts())
        ->pending_devices_count->toBe($pendingCount)
        ->complete_devices_count->toBe($completeCount)
        ->total_devices_count->toBe($totalCount);
})->with([
    'received' => [DeviceStatus::Received, 2, 1, 3],
    'on_hold' => [DeviceStatus::OnHold, 2, 1, 3],
    'under_repair' => [DeviceStatus::UnderRepair, 2, 1, 3],
    'ready' => [DeviceStatus::Ready, 1, 2, 3],
    'delivered' => [DeviceStatus::Delivered, 1, 2, 3],
]);
