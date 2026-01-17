<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('renders reset password link screen', function () {
    // Act
    $response = get(route('password.request'));

    // Assert
    $response->assertOk();
});

it('sends reset password link when requested', function () {
    // Arrange
    Notification::fake();

    $user = User::factory()->create();

    // Act
    post(route('password.email'), ['email' => $user->email]);

    // Assert
    Notification::assertSentTo($user, ResetPassword::class);
});

it('renders reset password screen', function () {
    // Arrange
    Notification::fake();

    $user = User::factory()->create();

    // Act
    post(route('password.email'), ['email' => $user->email]);

    // Assert
    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $response = get(route('password.reset', $notification->token));

        $response->assertOk();

        return true;
    });
});

it('resets password with valid token', function () {
    // Arrange
    Notification::fake();

    $user = User::factory()->create();

    // Act
    post(route('password.email'), ['email' => $user->email]);

    // Assert
    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $response = post(route('password.update'), [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));

        return true;
    });
});

it('does not reset password with invalid token', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = post(route('password.update'), [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    // Assert
    $response->assertSessionHasErrors('email');
});
