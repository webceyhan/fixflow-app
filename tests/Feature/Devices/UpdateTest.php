<?php

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Models\Device;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the edit device form', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $device = Device::factory()->create();

    // Act
    $response = actingAs($manager)->get("/devices/{$device->id}/edit");

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('devices/Edit')
        ->has('device')
    );
});

it('validates required fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $device = Device::factory()->create();

    // Act
    $response = actingAs($manager)->put("/devices/{$device->id}", [
        'model' => '',
    ]);

    // Assert
    $response->assertSessionHasErrors(['model', 'brand']);
});

it('does not allow changing customer_id', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $device = Device::factory()->create();
    $originalCustomerId = $device->customer_id;

    // Act
    $response = actingAs($manager)->put("/devices/{$device->id}", [
        'model' => 'Updated Model',
        'brand' => 'Updated Brand',
        'type' => $device->type->value,
        'status' => $device->status->value,
    ]);

    // Assert
    $response->assertRedirect();
    expect($device->fresh()->customer_id)->toBe($originalCustomerId);
});

it('validates unique imei except for current device', function () {
    // Arrange
    $existingDevice = Device::factory()->create(['imei' => '123456789012345']);
    $manager = User::factory()->manager()->create();
    $device = Device::factory()->create(['imei' => '999999999999999']);

    // Act
    $response = actingAs($manager)->put("/devices/{$device->id}", [
        'model' => 'Test Model',
        'brand' => 'Test Brand',
        'imei' => '123456789012345',
        'type' => $device->type->value,
        'status' => $device->status->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['imei']);
});

it('allows keeping the same imei', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $device = Device::factory()->create(['imei' => '123456789012345']);

    // Act
    $response = actingAs($manager)->put("/devices/{$device->id}", [
        'model' => 'Updated Model',
        'brand' => $device->brand,
        'imei' => '123456789012345',
        'type' => $device->type->value,
        'status' => $device->status->value,
    ]);

    // Assert
    $response->assertRedirect();
    expect($device->fresh()->imei)->toBe('123456789012345');
});

it('validates unique serial number except for current device', function () {
    // Arrange
    $existingDevice = Device::factory()->create(['serial_number' => 'SN123456']);
    $manager = User::factory()->manager()->create();
    $device = Device::factory()->create(['serial_number' => 'SN999999']);

    // Act
    $response = actingAs($manager)->put("/devices/{$device->id}", [
        'model' => 'Test Model',
        'brand' => 'Test Brand',
        'serial_number' => 'SN123456',
        'type' => $device->type->value,
        'status' => $device->status->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['serial_number']);
});

it('validates warranty expire date is after purchase date', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $device = Device::factory()->create();

    // Act
    $response = actingAs($manager)->put("/devices/{$device->id}", [
        'model' => 'Test Model',
        'brand' => 'Test Brand',
        'purchase_date' => '2024-12-01',
        'warranty_expire_date' => '2024-11-01',
        'type' => $device->type->value,
        'status' => $device->status->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['warranty_expire_date']);
});

it('updates a device successfully', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $device = Device::factory()->create();
    $originalCustomerId = $device->customer_id;

    // Act
    $response = actingAs($manager)->put("/devices/{$device->id}", [
        'model' => 'Updated Model',
        'brand' => 'Updated Brand',
        'color' => 'New Color',
        'imei' => '111111111111111',
        'serial_number' => 'NEWSERIAL',
        'passcode' => 'newpass',
        'purchase_date' => '2024-06-01',
        'warranty_expire_date' => '2025-06-01',
        'note' => 'Updated note',
        'type' => DeviceType::Tablet->value,
        'status' => DeviceStatus::InService->value,
    ]);

    // Assert
    $response->assertRedirect();

    $device->refresh();
    expect($device->customer_id)->toBe($originalCustomerId);
    expect($device->model)->toBe('Updated Model');
    expect($device->brand)->toBe('Updated Brand');
    expect($device->color)->toBe('New Color');
    expect($device->type)->toBe(DeviceType::Tablet);
    expect($device->status)->toBe(DeviceStatus::InService);
});

it('can clear optional fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $device = Device::factory()->create([
        'color' => 'Red',
        'imei' => '123456789012345',
        'serial_number' => 'SN123',
        'passcode' => 'oldpass',
        'note' => 'Old note',
    ]);

    // Act
    $response = actingAs($manager)->put("/devices/{$device->id}", [
        'customer_id' => $device->customer_id,
        'model' => $device->model,
        'brand' => $device->brand,
        'color' => null,
        'imei' => null,
        'serial_number' => null,
        'passcode' => null,
        'purchase_date' => null,
        'warranty_expire_date' => null,
        'note' => null,
        'type' => $device->type->value,
        'status' => $device->status->value,
    ]);

    // Assert
    $response->assertRedirect();

    $device->refresh();
    expect($device->color)->toBeNull();
    expect($device->imei)->toBeNull();
    expect($device->serial_number)->toBeNull();
    expect($device->passcode)->toBeNull();
    expect($device->note)->toBeNull();
});

it('encrypts updated passcode', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $device = Device::factory()->create(['passcode' => 'oldpass']);

    // Act
    $response = actingAs($manager)->put("/devices/{$device->id}", [
        'customer_id' => $device->customer_id,
        'model' => $device->model,
        'brand' => $device->brand,
        'passcode' => 'newsecret',
        'type' => $device->type->value,
        'status' => $device->status->value,
    ]);

    // Assert
    $device->refresh();
    expect($device->passcode)->toBe('newsecret');

    // Check that the raw database value is encrypted
    $rawDevice = Device::withoutGlobalScopes()->find($device->id);
    expect($rawDevice->getAttributes()['passcode'])->not->toBe('newsecret');
});
