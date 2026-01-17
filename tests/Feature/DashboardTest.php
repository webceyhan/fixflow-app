<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('redirects guests to the login page', function () {
    // Act
    $response = get(route('dashboard'));

    // Assert
    $response->assertRedirect(route('login'));
});

it('allows authenticated users to visit the dashboard', function () {
    // Arrange
    $user = User::factory()->create();
    actingAs($user);

    // Act
    $response = get(route('dashboard'));

    // Assert
    $response->assertOk();
});
