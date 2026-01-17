<?php

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use App\Notifications\WelcomeUser;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;

it('shows the create user form', function () {
    // Arrange
    $admin = User::factory()->admin()->create();

    // Act
    $response = actingAs($admin)->get('/users/create');

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->component('users/Create'));
});

it('validates required fields', function () {
    // Arrange
    $admin = User::factory()->admin()->create();

    // Act
    $response = actingAs($admin)->post('/users', []);

    // Assert
    $response->assertSessionHasErrors(['name', 'email', 'phone']);
});

it('validates email format', function () {
    // Arrange
    $admin = User::factory()->admin()->create();

    // Act
    $response = actingAs($admin)->post('/users', [
        'name' => 'Test User',
        'email' => 'invalid-email',
        'phone' => '+1234567890',
        'role' => UserRole::Technician->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['email']);
});

it('validates unique email', function () {
    // Arrange
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);
    $admin = User::factory()->admin()->create();

    // Act
    $response = actingAs($admin)->post('/users', [
        'name' => 'Test User',
        'email' => 'existing@example.com',
        'phone' => '+1234567890',
        'role' => UserRole::Technician->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['email']);
});

it('validates unique phone', function () {
    // Arrange
    $existingUser = User::factory()->create(['phone' => '+1234567890']);
    $admin = User::factory()->admin()->create();

    // Act
    $response = actingAs($admin)->post('/users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '+1234567890',
        'role' => UserRole::Technician->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['phone']);
});

it('validates role values', function () {
    // Arrange
    $admin = User::factory()->admin()->create();

    // Act
    $response = actingAs($admin)->post('/users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '+1234567890',
        'role' => 'invalid-role',
    ]);

    // Assert
    $response->assertSessionHasErrors(['role']);
});

it('creates a new user successfully', function () {
    // Arrange
    Notification::fake();
    $admin = User::factory()->admin()->create();

    // Act
    $response = actingAs($admin)->post('/users', [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'phone' => '+1234567890',
        'role' => UserRole::Manager->value,
        'status' => UserStatus::Active->value,
    ]);

    // Assert
    $response->assertRedirect();

    $newUser = User::where('email', 'newuser@example.com')->first();
    expect($newUser)->not->toBeNull();
    expect($newUser->name)->toBe('New User');
    expect($newUser->phone)->toBe('+1234567890');
    expect($newUser->role)->toBe(UserRole::Manager);
    expect($newUser->password)->not->toBeNull();

    Notification::assertSentTo($newUser, WelcomeUser::class);
});
