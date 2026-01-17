<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

it('deletes a user successfully', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    // Act
    $response = actingAs($admin)->delete("/users/{$user->id}");

    // Assert
    $response->assertRedirect('/users');
    expect(User::find($user->id))->toBeNull();
});
