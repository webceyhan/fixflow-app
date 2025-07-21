<?php

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Enums\TicketStatus;
use App\Models\Concerns\HasProgress;
use App\Models\Concerns\HasStatus;
use App\Models\Concerns\HasType;
use App\Models\Concerns\HasWarranty;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Ticket;
use App\Observers\DeviceObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Device::class,
    concerns: [
        HasProgress::class,
        HasStatus::class,
        HasType::class,
        HasWarranty::class,
    ],
    observers: [
        DeviceObserver::class,
    ],
    defaults: [
        'type' => DeviceType::Other,
        'status' => DeviceStatus::Received,
        'pending_tickets_count' => 0,
        'complete_tickets_count' => 0,
        'total_tickets_count' => 0,
    ],
    fillables: [
        'model',
        'brand',
        'serial_number',
        'purchase_date',
        'warranty_expire_date',
        'type',
        'status',
    ],
    casts: [
        'purchase_date' => 'date',
    ],
    relations: [
        'customer' => BelongsTo::class,
        'tickets' => HasMany::class,
    ]
);

// RELATION TESTS //////////////////////////////////////////////////////////////////////////////////

it('has many tickets relationship', function () {
    // Arrange
    $device = Device::factory()->create();
    Ticket::factory()->count(2)->forDevice($device)->create();

    // Assert
    expect($device->tickets)->toHaveCount(2);
    expect($device->tickets->first())->toBeInstanceOf(Ticket::class);
    expect($device->tickets->first()->device_id)->toBe($device->id);
});

it('belongs to customer relationship', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();

    // Assert
    expect($device->customer)->toBeInstanceOf(Customer::class);
    expect($device->customer->id)->toBe($customer->id);
});

// METHOD TESTS ////////////////////////////////////////////////////////////////////////////////////

it('can fill ticket counts', function (TicketStatus $status, int $pendingCount, int $completeCount) {
    // Arrange
    $device = Device::factory()->create();
    Ticket::factory()->forDevice($device)->pending()->create();
    Ticket::factory()->forDevice($device)->complete()->create();
    Ticket::factory()->forDevice($device)->ofStatus($status)->create();

    // Act
    $device->fillTicketCounts();

    // Assert
    expect($device->pending_tickets_count)->toBe($pendingCount);
    expect($device->complete_tickets_count)->toBe($completeCount);
    expect($device->total_tickets_count)->toBe($pendingCount + $completeCount);
})->with([
    'new' => [TicketStatus::New, 2, 1],
    'in_progress' => [TicketStatus::InProgress, 2, 1],
    'on_hold' => [TicketStatus::OnHold, 2, 1],
    'resolved' => [TicketStatus::Resolved, 1, 2],
    'closed' => [TicketStatus::Closed, 1, 2],
]);
