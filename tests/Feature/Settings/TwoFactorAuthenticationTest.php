<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;

use function Pest\Laravel\actingAs;

it('renders two factor settings page', function () {
    // Arrange
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->create();

    // Act & Assert
    actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('two-factor.show'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/TwoFactor')
            ->where('twoFactorEnabled', false)
        );
});

it('requires password confirmation for two factor settings page when enabled', function () {
    // Arrange
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    $user = User::factory()->create();

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    // Act
    $response = actingAs($user)
        ->get(route('two-factor.show'));

    // Assert
    $response->assertRedirect(route('password.confirm'));
});

it('does not require password confirmation for two factor settings page when disabled', function () {
    // Arrange
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    $user = User::factory()->create();

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => false,
    ]);

    // Act & Assert
    actingAs($user)
        ->get(route('two-factor.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/TwoFactor')
        );
});

it('returns forbidden response for two factor settings page when two factor is disabled', function () {
    // Arrange
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    config(['fortify.features' => []]);

    $user = User::factory()->create();

    // Act & Assert
    actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('two-factor.show'))
        ->assertForbidden();
});
