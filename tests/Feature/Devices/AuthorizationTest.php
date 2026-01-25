<?php

use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\Device;
use App\Models\User;

use function Pest\Laravel\actingAs;

dataset('permissionsForDevices', [ // role, write access
    'Admin' => [UserRole::Admin, true],
    'Manager' => [UserRole::Manager, true],
    'Technician' => [UserRole::Technician, true],
]);

it('handles viewing devices list correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();

    // Act & Assert
    actingAs($user)->get('/devices')->assertSuccessful();
})->with('permissionsForDevices');

it('handles viewing device details correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $device = Device::factory()->create();

    // Act & Assert
    actingAs($user)->get("/devices/{$device->id}")->assertSuccessful();
})->with('permissionsForDevices');

it('handles device creation correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $customer = Customer::factory()->create();

    // Act & Assert
    actingAs($user)->get("/customers/{$customer->id}/devices/create")->assertSuccessful();
})->with('permissionsForDevices');

it('handles device editing correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $device = Device::factory()->create();

    // Act & Assert
    actingAs($user)->get("/devices/{$device->id}/edit")->assertSuccessful();
})->with('permissionsForDevices');

it('handles device deletion correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $device = Device::factory()->create();

    // Act & Assert
    actingAs($user)->delete("/devices/{$device->id}")->assertRedirect('/devices');
})->with('permissionsForDevices');
