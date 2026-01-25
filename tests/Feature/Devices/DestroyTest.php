<?php

use App\Models\Device;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('deletes a device successfully', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $device = Device::factory()->create();

    // Act
    $response = actingAs($manager)->delete("/devices/{$device->id}");

    // Assert
    $response->assertRedirect(route('devices.index'));
    expect(Device::find($device->id))->toBeNull();
});
