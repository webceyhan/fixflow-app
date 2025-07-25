<?php

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a user with valid attributes', function () {
    // Arrange & Act
    $user = User::factory()->create();

    // Assert
    expect($user->name)->not->toBeEmpty();
    expect($user->email)->not->toBeEmpty();
    expect($user->phone)->not->toBeEmpty();
    expect($user->role)->toBeInstanceOf(UserRole::class);
    expect($user->status)->toBeInstanceOf(UserStatus::class);
});

it('can create user of role', function (UserRole $role) {
    // Act
    $user = User::factory()->ofRole($role)->create();

    // Assert
    expect($user->role)->toBe($role);
})->with(UserRole::cases());

it('can create user unverified', function () {
    // Act
    $user = User::factory()->unverified()->create();

    // Assert
    expect($user->email_verified_at)->toBeNull();
});

it('can update user', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $user->update([
        'name' => 'Bill Gates',
        'email' => 'bill.gates@mail.com',
        'phone' => '+1234567890',
        'role' => UserRole::Manager,
        'status' => UserStatus::Terminated,
    ]);

    // Assert
    expect($user->name)->toBe('Bill Gates');
    expect($user->email)->toBe('bill.gates@mail.com');
    expect($user->phone)->toBe('+1234567890');
    expect($user->role)->toBe(UserRole::Manager);
    expect($user->status)->toBe(UserStatus::Terminated);
});

it('can delete user', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $user->delete();

    // Assert
    expect(User::find($user->id))->toBeNull();
});

it('can determine if user is admin', function () {
    // Act
    $admin = User::factory()->admin()->create();
    $technician = User::factory()->create();

    // Assert
    expect($admin->isAdmin())->toBeTrue();
    expect($technician->isAdmin())->toBeFalse();
});

it('can filter users by role scope', function (UserRole $role) {
    // Arrange
    User::factory()->ofRole($role)->create();

    // Act
    $users = User::ofRole($role);

    // Assert
    expect($users->count())->toBe(1);
    expect($users->first()->role)->toBe($role);
})->with(UserRole::cases());

it('can determine if user is active', function () {
    // Act
    $activeUser = User::factory()->ofStatus(UserStatus::Active)->create();
    $suspendedUser = User::factory()->ofStatus(UserStatus::Suspended)->create();

    // Assert
    expect($activeUser->isActive())->toBeTrue();
    expect($suspendedUser->isActive())->toBeFalse();
});
