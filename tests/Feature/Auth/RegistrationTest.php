<?php

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('renders registration screen', function () {
    // Act
    $response = get(route('register'));

    // Assert
    $response->assertOk();
});

it('registers new users', function () {
    // Act
    $response = post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '+1234567890',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    // Assert
    assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
