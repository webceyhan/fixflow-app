<?php

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Models\Customer;
use App\Models\Device;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the create device form', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->create();

    // Act
    $response = actingAs($manager)->get("/customers/{$customer->id}/devices/create");

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->component('devices/Create'));
});

it('validates required fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->create();

    // Act
    $response = actingAs($manager)->post("/customers/{$customer->id}/devices", []);

    // Assert
    $response->assertSessionHasErrors(['model', 'brand']);
});

it('returns 404 when customer does not exist', function () {
    // Arrange
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/customers/9999/devices', [
        'model' => 'Test Model',
        'brand' => 'Test Brand',
        'type' => DeviceType::Phone->value,
    ]);

    // Assert
    $response->assertNotFound();
});

it('validates unique imei', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $existingDevice = Device::factory()->create(['imei' => '123456789012345']);
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/customers/{$customer->id}/devices", [
        'model' => 'Test Model',
        'brand' => 'Test Brand',
        'imei' => '123456789012345',
        'type' => DeviceType::Phone->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['imei']);
});

it('validates unique serial number', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $existingDevice = Device::factory()->create(['serial_number' => 'SN123456']);
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/customers/{$customer->id}/devices", [
        'model' => 'Test Model',
        'brand' => 'Test Brand',
        'serial_number' => 'SN123456',
        'type' => DeviceType::Phone->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['serial_number']);
});

it('validates warranty expire date is after purchase date', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/customers/{$customer->id}/devices", [
        'model' => 'Test Model',
        'brand' => 'Test Brand',
        'purchase_date' => '2024-12-01',
        'warranty_expire_date' => '2024-11-01',
        'type' => DeviceType::Phone->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['warranty_expire_date']);
});

it('creates a device successfully with all fields', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/customers/{$customer->id}/devices", [
        'model' => 'iPhone 15 Pro',
        'brand' => 'Apple',
        'color' => 'Space Black',
        'imei' => '123456789012345',
        'serial_number' => 'SN123456',
        'passcode' => '1234',
        'purchase_date' => '2024-01-15',
        'warranty_expire_date' => '2025-01-15',
        'note' => 'Customer device',
        'type' => DeviceType::Phone->value,
        'status' => DeviceStatus::Received->value,
    ]);

    // Assert
    $response->assertRedirect();

    $device = Device::where('imei', '123456789012345')->first();
    expect($device)->not->toBeNull();
    expect($device->model)->toBe('iPhone 15 Pro');
    expect($device->brand)->toBe('Apple');
    expect($device->color)->toBe('Space Black');
    expect($device->type)->toBe(DeviceType::Phone);
    expect($device->status)->toBe(DeviceStatus::Received);
    expect($device->customer_id)->toBe($customer->id);
});

it('creates a device with minimal required fields', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/customers/{$customer->id}/devices", [
        'model' => 'MacBook Pro',
        'brand' => 'Apple',
        'type' => DeviceType::Laptop->value,
        'status' => DeviceStatus::Received->value,
    ]);

    // Assert
    $response->assertRedirect();

    $device = Device::where('model', 'MacBook Pro')->first();
    expect($device)->not->toBeNull();
    expect($device->customer_id)->toBe($customer->id);
    expect($device->color)->toBeNull();
    expect($device->imei)->toBeNull();
});

it('encrypts passcode when storing', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/customers/{$customer->id}/devices", [
        'model' => 'Test Device',
        'brand' => 'Test Brand',
        'passcode' => 'secret123',
        'type' => DeviceType::Phone->value,
        'status' => DeviceStatus::Received->value,
    ]);

    // Assert
    $device = Device::where('model', 'Test Device')->first();
    expect($device->passcode)->toBe('secret123');

    // Check that the raw database value is encrypted
    $rawDevice = Device::withoutGlobalScopes()->find($device->id);
    expect($rawDevice->getAttributes()['passcode'])->not->toBe('secret123');
});
