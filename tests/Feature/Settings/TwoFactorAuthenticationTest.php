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
        ->get(route('security.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/Security')
            ->where('canManageTwoFactor', true)
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
        ->get(route('security.edit'));

    // Assert
    $response->assertRedirect(route('password.confirm'));
});

it('renders security settings page when two factor is disabled', function () {
    $user = User::factory()->create();

    config(['fortify.features' => []]);

    // Act & Assert
    actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('security.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/Security')
            ->where('canManageTwoFactor', false)
        );
});

it('exposes passkey well-known endpoints route', function () {
    $response = $this->get(route('well-known.passkeys'));

    $response
        ->assertOk()
        ->assertJson([
            'enroll' => route('security.edit'),
            'manage' => route('security.edit'),
        ]);
});
