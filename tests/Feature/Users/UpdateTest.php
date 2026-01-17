<?php

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the edit user form', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    // Act
    $response = actingAs($admin)->get("/users/{$user->id}/edit");

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Edit')
        ->has('user')
    );
});

it('validates required fields', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    // Act
    $response = actingAs($admin)->put("/users/{$user->id}", [
        'name' => '',
        'email' => '',
        'phone' => '',
    ]);

    // Assert
    $response->assertSessionHasErrors(['name', 'email', 'phone']);
});

it('validates email format', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    // Act
    $response = actingAs($admin)->put("/users/{$user->id}", [
        'name' => 'Test User',
        'email' => 'invalid-email',
        'phone' => '+1234567890',
        'role' => $user->role->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['email']);
});

it('validates unique email except for current user', function () {
    // Arrange
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create(['email' => 'current@example.com']);

    // Act
    $response = actingAs($admin)->put("/users/{$user->id}", [
        'name' => 'Test User',
        'email' => 'existing@example.com',
        'phone' => '+1234567890',
        'role' => $user->role->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['email']);
});

it('validates unique phone except for current user', function () {
    // Arrange
    $existingUser = User::factory()->create(['phone' => '+1234567890']);
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    // Act
    $response = actingAs($admin)->put("/users/{$user->id}", [
        'name' => 'Test User',
        'email' => $user->email,
        'phone' => '+1234567890',
        'role' => $user->role->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['phone']);
});

it('allows keeping the same phone', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create(['phone' => '+1234567890']);

    // Act
    $response = actingAs($admin)->put("/users/{$user->id}", [
        'name' => 'Updated Name',
        'email' => $user->email,
        'phone' => '+1234567890',
        'role' => $user->role->value,
        'status' => $user->status->value,
    ]);

    // Assert
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
});

it('validates role values', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    // Act
    $response = actingAs($admin)->put("/users/{$user->id}", [
        'name' => 'Test User',
        'email' => $user->email,
        'phone' => $user->phone,
        'role' => 'invalid-role',
        'status' => $user->status->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['role']);
});

it('allows keeping the same email', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create(['email' => 'current@example.com']);

    // Act
    $response = actingAs($admin)->put("/users/{$user->id}", [
        'name' => 'Updated Name',
        'email' => 'current@example.com',
        'phone' => $user->phone,
        'role' => $user->role->value,
        'status' => $user->status->value,
    ]);

    // Assert
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
});

it('updates a user successfully', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create(['name' => 'Old Name', 'email' => 'old@example.com']);
    $oldPassword = $user->password;

    // Act
    $response = actingAs($admin)->put("/users/{$user->id}", [
        'name' => 'New Name',
        'email' => 'new@example.com',
        'phone' => '+9876543210',
        'role' => UserRole::Admin->value,
        'status' => UserStatus::Active->value,
    ]);

    // Assert
    $response->assertRedirect();

    $user->refresh();
    expect($user->name)->toBe('New Name');
    expect($user->email)->toBe('new@example.com');
    expect($user->phone)->toBe('+9876543210');
    expect($user->role)->toBe(UserRole::Admin);
    expect($user->password)->toBe($oldPassword); // Password unchanged
});
