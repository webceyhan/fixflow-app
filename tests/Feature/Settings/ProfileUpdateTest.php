<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;

it('displays profile page', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = actingAs($user)
        ->get(route('profile.edit'));

    // Assert
    $response->assertOk();
});

it('updates profile information', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    // Assert
    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    $user->refresh();

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

it('keeps email verification status unchanged when the email address is unchanged', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    // Assert
    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

it('deletes user account', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = actingAs($user)
        ->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

    // Assert
    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('home'));

    assertGuest();
    expect($user->fresh())->toBeNull();
});

it('requires correct password to delete account', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = actingAs($user)
        ->from(route('profile.edit'))
        ->delete(route('profile.destroy'), [
            'password' => 'wrong-password',
        ]);

    // Assert
    $response
        ->assertSessionHasErrors('password')
        ->assertRedirect(route('profile.edit'));

    expect($user->fresh())->not->toBeNull();
});
