<?php

use App\Enums\DeviceStatus;
use App\Models\Concerns\Contactable;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Customer::class,
    concerns: [
        Contactable::class,
    ],
    defaults: [
        'pending_devices_count' => 0,
        'complete_devices_count' => 0,
        'total_devices_count' => 0,
    ],
    fillables: [
        'name',
        'company',
        'vat_number',
        'email',
        'phone',
        'address',
        'note',
    ],
    relations: [
        'devices' => HasMany::class,
        'tickets' => HasManyThrough::class,
    ]
);

// RELATION TESTS //////////////////////////////////////////////////////////////////////////////////

it('has many devices relationship', function () {
    // Arrange
    $customer = Customer::factory()->create();
    Device::factory()->count(2)->forCustomer($customer)->create();

    // Assert
    expect($customer->devices)->toHaveCount(2);
    expect($customer->devices->first())->toBeInstanceOf(Device::class);
    expect($customer->devices->first()->customer_id)->toBe($customer->id);
});

it('has many tickets relationship through devices', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();
    Ticket::factory()->count(2)->forDevice($device)->create();

    // Assert
    expect($customer->tickets)->toHaveCount(2);
    expect($customer->tickets->first())->toBeInstanceOf(Ticket::class);
});

// METHOD TESTS ////////////////////////////////////////////////////////////////////////////////////

it('can fill device counts', function (DeviceStatus $status, int $pendingCount, int $completeCount) {
    // Arrange
    $customer = Customer::factory()->create();
    Device::factory()->forCustomer($customer)->pending()->create();
    Device::factory()->forCustomer($customer)->complete()->create();
    Device::factory()->forCustomer($customer)->ofStatus($status)->create();

    // Act
    $customer->fillDeviceCounts();

    // Assert
    expect($customer->pending_devices_count)->toBe($pendingCount);
    expect($customer->complete_devices_count)->toBe($completeCount);
    expect($customer->total_devices_count)->toBe($pendingCount + $completeCount);
})->with([
    'received' => [DeviceStatus::Received, 2, 1],
    'on_hold' => [DeviceStatus::OnHold, 2, 1],
    'under_repair' => [DeviceStatus::UnderRepair, 2, 1],
    'ready' => [DeviceStatus::Ready, 1, 2],
    'delivered' => [DeviceStatus::Delivered, 1, 2],
]);
