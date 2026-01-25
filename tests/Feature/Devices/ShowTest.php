<?php

use App\Models\Device;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('allows authenticated users to view device details', function () {
    // Arrange
    $viewer = User::factory()->create();
    $device = Device::factory()->create();

    // Act
    $response = actingAs($viewer)->get(route('devices.show', $device));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('devices/Show')
        ->has('device')
        ->where('device.id', $device->id)
    );
});

it('loads customer relationship on device show', function () {
    // Arrange
    $viewer = User::factory()->create();
    $device = Device::factory()->create();

    // Act
    $response = actingAs($viewer)->get(route('devices.show', $device));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('device.customer')
    );
});
