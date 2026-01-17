<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\actingAs;

it('displays password update page', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = actingAs($user)
        ->get(route('user-password.edit'));

    // Assert
    $response->assertOk();
});

it('updates password', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = actingAs($user)
        ->from(route('user-password.edit'))
        ->put(route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    // Assert
    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('user-password.edit'));

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

it('requires correct password to update password', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = actingAs($user)
        ->from(route('user-password.edit'))
        ->put(route('user-password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    // Assert
    $response
        ->assertSessionHasErrors('current_password')
        ->assertRedirect(route('user-password.edit'));
});
