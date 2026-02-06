<?php

use App\Enums\UserStatus;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('refreshes assignable users after user is created', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $admin = User::factory()->admin()->create(['name' => "Admin User {$uid}"]);

    // First request with only admin
    $response1 = actingAs($admin)->get('/');
    $response1->assertInertia(fn ($page) => $page->has('assignableUsers', 1));

    // Act - Create new user (observer should clear cache)
    $newUser = User::factory()->active()->create([
        'name' => "New User {$uid}",
    ]);

    // Assert - Second request should have updated data
    $response2 = actingAs($admin)->get('/');
    $response2->assertInertia(fn ($page) => $page
        ->has('assignableUsers', 2)
        ->where('assignableUsers.0.name', $admin->name)
        ->where('assignableUsers.1.name', $newUser->name)
    );
});

it('refreshes assignable users after user is updated', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $admin = User::factory()->admin()->create(['name' => "Admin User {$uid}"]);
    $user = User::factory()->active()->create([
        'name' => "Original Name {$uid}",
    ]);

    // First request
    $response1 = actingAs($admin)->get('/');
    $response1->assertInertia(fn ($page) => $page->has('assignableUsers', 2));

    // Act - Update user name (observer should clear cache)
    $user->update(['name' => "Updated Name {$uid}"]);

    // Assert - Second request should have updated data
    $response2 = actingAs($admin)->get('/');
    $response2->assertInertia(fn ($page) => $page
        ->has('assignableUsers', 2)
        ->where('assignableUsers.0.name', $admin->name)
        ->where('assignableUsers.1.name', "Updated Name {$uid}")
    );
});

it('refreshes assignable users after user status changes', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $admin = User::factory()->admin()->create(['name' => "Admin User {$uid}"]);
    $user = User::factory()->active()->create([
        'name' => "User To Suspend {$uid}",
    ]);

    // First request with 2 users
    $response1 = actingAs($admin)->get('/');
    $response1->assertInertia(fn ($page) => $page->has('assignableUsers', 2));

    // Act - Suspend user (observer should clear cache)
    $user->update(['status' => UserStatus::Suspended]);

    // Assert - Second request should exclude suspended user
    $response2 = actingAs($admin)->get('/');
    $response2->assertInertia(fn ($page) => $page->has('assignableUsers', 1));
});

it('refreshes assignable users after user is deleted', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $admin = User::factory()->admin()->create(['name' => "Admin User {$uid}"]);
    $user = User::factory()->active()->create([
        'name' => "User To Delete {$uid}",
    ]);

    // First request with 2 users
    $response1 = actingAs($admin)->get('/');
    $response1->assertInertia(fn ($page) => $page->has('assignableUsers', 2));

    // Act - Delete user (observer should clear cache)
    $user->delete();

    // Assert - Second request should have only admin
    $response2 = actingAs($admin)->get('/');
    $response2->assertInertia(fn ($page) => $page->has('assignableUsers', 1));
});
