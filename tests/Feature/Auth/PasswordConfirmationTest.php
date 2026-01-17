<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('renders confirm password screen', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = actingAs($user)->get(route('password.confirm'));

    // Assert
    $response->assertOk();

    $response->assertInertia(fn (Assert $page) => $page
        ->component('auth/ConfirmPassword')
    );
});

it('requires authentication for password confirmation', function () {
    // Act
    $response = get(route('password.confirm'));

    // Assert
    $response->assertRedirect(route('login'));
});
