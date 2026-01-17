<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;

it('sends verification notification', function () {
    // Arrange
    Notification::fake();

    $user = User::factory()->unverified()->create();

    // Act
    actingAs($user)
        ->post(route('verification.send'))
        ->assertRedirect(route('home'));

    // Assert
    Notification::assertSentTo($user, VerifyEmail::class);
});

it('does not send verification notification if email is verified', function () {
    // Arrange
    Notification::fake();

    $user = User::factory()->create();

    // Act
    actingAs($user)
        ->post(route('verification.send'))
        ->assertRedirect(route('dashboard', absolute: false));

    // Assert
    Notification::assertNothingSent();
});
