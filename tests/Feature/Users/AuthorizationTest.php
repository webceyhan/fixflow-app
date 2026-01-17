<?php

use App\Enums\UserRole;
use App\Models\User;

use function Pest\Laravel\actingAs;

dataset('permissionsForUsers', [ // role, write access
    'Admin' => [UserRole::Admin, true],
    'Manager' => [UserRole::Manager, false],
    'Technician' => [UserRole::Technician, false],
]);

it('handles viewing users list correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();

    // Act & Assert
    actingAs($user)->get('/users')->assertSuccessful();
})->with('permissionsForUsers');

it('handles viewing user details correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $targetUser = User::factory()->create();

    // Act & Assert
    actingAs($user)->get("/users/{$targetUser->id}")->assertSuccessful();
})->with('permissionsForUsers');

it('handles user creation correctly', function (UserRole $role, bool $writeAccess) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();

    // Act
    $response = actingAs($user)->get('/users/create');

    // Assert
    $writeAccess ? $response->assertSuccessful() : $response->assertForbidden();
})->with('permissionsForUsers');

it('handles user editing correctly', function (UserRole $role, bool $writeAccess) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $targetUser = User::factory()->create();

    // Act
    $response = actingAs($user)->get("/users/{$targetUser->id}/edit");

    // Assert
    $writeAccess ? $response->assertSuccessful() : $response->assertForbidden();
})->with('permissionsForUsers');

it('handles user deletion correctly', function (UserRole $role, bool $writeAccess) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $targetUser = User::factory()->create();

    // Act
    $response = actingAs($user)->delete("/users/{$targetUser->id}");

    // Assert
    $writeAccess ? $response->assertRedirect('/users') : $response->assertForbidden();
})->with('permissionsForUsers');

it('prevents admin from deleting themselves', function () {
    // Arrange
    $admin = User::factory()->admin()->create();

    // Act
    $response = actingAs($admin)->delete("/users/{$admin->id}");

    // Assert
    $response->assertForbidden();
});
