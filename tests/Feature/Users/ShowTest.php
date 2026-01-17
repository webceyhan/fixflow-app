<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

it('allows authenticated users to view a user profile', function () {
    // Arrange
    $viewer = User::factory()->create();
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    // Act
    $response = actingAs($viewer)->get(route('users.show', $user));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Show')
        ->has('user')
        ->where('user.id', $user->id)
        ->where('user.name', 'John Doe')
        ->where('user.email', 'john@example.com')
    );
});

it('allows authenticated users to view their own profile', function () {
    // Arrange
    $user = User::factory()->create(['name' => 'Jane Smith']);

    // Act
    $response = actingAs($user)->get(route('users.show', $user));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Show')
        ->where('user.id', $user->id)
        ->where('user.name', 'Jane Smith')
    );
});
