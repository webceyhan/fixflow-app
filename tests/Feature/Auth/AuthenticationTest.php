<?php

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Features;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('renders login screen', function () {
    // Act
    $response = get(route('login'));

    // Assert
    $response->assertOk();
});

it('authenticates users using the login screen', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    // Assert
    assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

it('redirects users with two factor enabled to two factor challenge', function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    // Arrange
    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->create();

    $user->forceFill([
        'two_factor_secret' => encrypt('test-secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    // Act
    $response = post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    // Assert
    $response->assertRedirect(route('two-factor.login'));
    $response->assertSessionHas('login.id', $user->id);
    assertGuest();
});

it('does not authenticate users with invalid password', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    // Assert
    assertGuest();
});

it('logs out users', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = actingAs($user)->post(route('logout'));

    // Assert
    assertGuest();
    $response->assertRedirect(route('home'));
});

it('rate limits users', function () {
    // Arrange
    $user = User::factory()->create();
    RateLimiter::increment(md5('login'.implode('|', [$user->email, '127.0.0.1'])), amount: 5);

    // Act
    $response = post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    // Assert
    $response->assertTooManyRequests();
});
