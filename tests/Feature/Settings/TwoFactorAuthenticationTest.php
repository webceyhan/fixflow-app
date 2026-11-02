<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

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

it('renders two factor settings page without password confirmation', function () {
    // Arrange
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    $user = User::factory()->create();

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    // Act & Assert
    $response = actingAs($user)
        ->get(route('security.edit'));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('settings/Security')
        ->where('canManageTwoFactor', true)
    );
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
    $response = get(route('well-known.passkeys'));

    $response
        ->assertOk()
        ->assertJson([
            'enroll' => route('security.edit'),
            'manage' => route('security.edit'),
        ]);
});
