<?php

use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\User;

use function Pest\Laravel\actingAs;

dataset('permissionsForCustomers', [ // role, write access
    'Admin' => [UserRole::Admin, true],
    'Manager' => [UserRole::Manager, true],
    'Technician' => [UserRole::Technician, false],
]);

it('handles viewing customers list correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();

    // Act & Assert
    actingAs($user)->get('/customers')->assertSuccessful();
})->with('permissionsForCustomers');

it('handles viewing customer details correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $customer = Customer::factory()->create();

    // Act & Assert
    actingAs($user)->get("/customers/{$customer->id}")->assertSuccessful();
})->with('permissionsForCustomers');

it('handles customer creation correctly', function (UserRole $role, bool $writeAccess) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();

    // Act
    $response = actingAs($user)->get('/customers/create');

    // Assert
    $writeAccess ? $response->assertSuccessful() : $response->assertForbidden();
})->with('permissionsForCustomers');

it('handles customer editing correctly', function (UserRole $role, bool $writeAccess) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $customer = Customer::factory()->create();

    // Act
    $response = actingAs($user)->get("/customers/{$customer->id}/edit");

    // Assert
    $writeAccess ? $response->assertSuccessful() : $response->assertForbidden();
})->with('permissionsForCustomers');

it('handles customer deletion correctly', function (UserRole $role, bool $writeAccess) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $customer = Customer::factory()->create();

    // Act
    $response = actingAs($user)->delete("/customers/{$customer->id}");

    // Assert
    $writeAccess ? $response->assertRedirect('/customers') : $response->assertForbidden();
})->with('permissionsForCustomers');
