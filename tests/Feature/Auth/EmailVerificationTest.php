<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\actingAs;

it('renders email verification screen', function () {
    // Arrange
    $user = User::factory()->unverified()->create();

    // Act
    $response = actingAs($user)->get(route('verification.notice'));

    // Assert
    $response->assertOk();
});

it('verifies email', function () {
    // Arrange
    $user = User::factory()->unverified()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    // Act
    $response = actingAs($user)->get($verificationUrl);

    // Assert
    Event::assertDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
});

it('does not verify email with invalid hash', function () {
    // Arrange
    $user = User::factory()->unverified()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    // Act
    actingAs($user)->get($verificationUrl);

    // Assert
    Event::assertNotDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

it('does not verify email with invalid user id', function () {
    // Arrange
    $user = User::factory()->unverified()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => 123, 'hash' => sha1($user->email)]
    );

    // Act
    actingAs($user)->get($verificationUrl);

    // Assert
    Event::assertNotDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

it('redirects verified user to dashboard from verification prompt', function () {
    // Arrange
    $user = User::factory()->create();

    Event::fake();

    // Act
    $response = actingAs($user)->get(route('verification.notice'));

    // Assert
    Event::assertNotDispatched(Verified::class);
    $response->assertRedirect(route('dashboard', absolute: false));
});

it('redirects already verified user visiting verification link without firing event again', function () {
    // Arrange
    $user = User::factory()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    // Act
    actingAs($user)->get($verificationUrl)
        ->assertRedirect(route('dashboard', absolute: false).'?verified=1');

    // Assert
    Event::assertNotDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});
