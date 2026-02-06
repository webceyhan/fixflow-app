<?php

use App\Actions\Users\GetAssignableUsers;
use App\Enums\UserRole;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shares assignable users via inertia with id, name, and role', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $admin = User::factory()->admin()->create(['name' => "Admin User {$uid}"]);
    $manager = User::factory()->manager()->active()->create([
        'name' => "Manager User {$uid}",
    ]);
    $technician = User::factory()->technician()->active()->create([
        'name' => "Technician User {$uid}",
    ]);

    // Act
    $response = actingAs($admin)->get('/');

    // Assert
    $response->assertInertia(fn ($page) => $page
        ->has('assignableUsers', 3)
        ->where('assignableUsers.0.name', $admin->name)
        ->where('assignableUsers.1.name', $manager->name)
        ->where('assignableUsers.2.name', $technician->name)
        ->where('assignableUsers.0.role', UserRole::Admin->value)
        ->where('assignableUsers.1.role', UserRole::Manager->value)
        ->where('assignableUsers.2.role', UserRole::Technician->value)
        ->has('assignableUsers.0.id')
    );
});

it('excludes suspended and terminated users from inertia share', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $admin = User::factory()->admin()->create(['name' => "Admin User {$uid}"]);
    $activeUser = User::factory()->active()->create([
        'name' => "Active User {$uid}",
    ]);
    User::factory()->suspended()->create([
        'name' => "Suspended User {$uid}",
    ]);
    User::factory()->terminated()->create([
        'name' => "Terminated User {$uid}",
    ]);

    // Act
    $response = actingAs($admin)->get('/');

    // Assert
    $response->assertInertia(fn ($page) => $page
        ->has('assignableUsers', 2) // Only admin and active user
        ->where('assignableUsers.0.name', $activeUser->name)
        ->where('assignableUsers.1.name', $admin->name)
    );
});

it('caches assignable users on multiple requests', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    User::factory()->active()->create();

    // Act
    $response1 = actingAs($admin)->get('/');
    $response2 = actingAs($admin)->get('/');

    // Assert
    $response1->assertInertia(fn ($page) => $page->has('assignableUsers', 2));
    $response2->assertInertia(fn ($page) => $page->has('assignableUsers', 2));
    expect(cache()->has(GetAssignableUsers::class))->toBeTrue();
});
