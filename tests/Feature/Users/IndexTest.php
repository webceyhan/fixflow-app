<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the users list to authenticated users', function () {
    // Arrange
    $user = User::factory()->create();
    $otherUsers = User::factory()->count(3)->create();

    // Act
    $response = actingAs($user)->get(route('users.index'));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->has('users.data', 4)
        ->has('users.links')
        ->has('filter')
    );
});

it('allows searching users by name', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $user = User::factory()->create(['name' => "John {$uid} Doe"]);
    User::factory()->create(['name' => 'Jane Smith']);
    User::factory()->create(['name' => "Bob Johnson {$uid}"]);

    // Act
    $response = actingAs($user)->get(route('users.index', ['filter' => ['search' => $uid]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->has('users.data', 2)
    );
});

it('allows searching users by email', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $user = User::factory()->create(['email' => "john{$uid}@example.com"]);
    User::factory()->create(['email' => 'jane@example.com']);
    User::factory()->create(['email' => "bob{$uid}@test.com"]);

    // Act
    $response = actingAs($user)->get(route('users.index', ['filter' => ['search' => $uid]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->has('users.data', 2)
    );
});

it('allows searching users by phone', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $user = User::factory()->create();
    User::factory()->create(['phone' => "+{$uid}1234567891"]);
    User::factory()->create(['phone' => "+9876543210{$uid}"]);

    // Act
    $response = actingAs($user)->get(route('users.index', ['filter' => ['search' => $uid]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->has('users.data', 2)
    );
});

it('allows filtering users by role', function () {
    // Arrange
    $admin = User::factory()->admin()->create();

    $admin1 = User::factory()->admin()->create(['name' => 'Alice Admin']);
    $admin2 = User::factory()->admin()->create(['name' => 'Bob Admin']);

    $manager1 = User::factory()->manager()->create(['name' => 'Charlie Manager']);
    $manager2 = User::factory()->manager()->create(['name' => 'Dana Manager']);
    $manager3 = User::factory()->manager()->create(['name' => 'Eve Manager']);

    $tech1 = User::factory()->technician()->create(['name' => 'Frank Tech']);
    $tech2 = User::factory()->technician()->create(['name' => 'Grace Tech']);

    // Act & Assert - Filter by admin role
    $response = actingAs($admin)->get(route('users.index', ['filter' => ['role' => 'admin']]));
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->where('users.data.0.name', fn ($name) => in_array($name, ['Alice Admin', 'Bob Admin', $admin->name]))
    );

    // Act & Assert - Filter by manager role
    $response = actingAs($admin)->get(route('users.index', ['filter' => ['role' => 'manager']]));
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->where('users.data.0.role', 'manager')
    );

    // Act & Assert - Filter by technician role
    $response = actingAs($admin)->get(route('users.index', ['filter' => ['role' => 'technician']]));
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->where('users.data.0.role', 'technician')
    );
});

it('allows filtering users by status', function () {
    // Arrange
    $admin = User::factory()->admin()->create();

    User::factory()->active()->count(3)->create();
    User::factory()->suspended()->count(2)->create();
    User::factory()->terminated()->create();

    // Act & Assert - Filter by active status
    $response = actingAs($admin)->get(route('users.index', ['filter' => ['status' => 'active']]));
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->where('users.data.0.status', 'active')
    );

    // Act & Assert - Filter by suspended status
    $response = actingAs($admin)->get(route('users.index', ['filter' => ['status' => 'suspended']]));
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->where('users.data.0.status', 'suspended')
    );

    // Act & Assert - Filter by terminated status
    $response = actingAs($admin)->get(route('users.index', ['filter' => ['status' => 'terminated']]));
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->where('users.data.0.status', 'terminated')
    );
});

it('paginates users correctly', function () {
    // Arrange
    $user = User::factory()->create();
    User::factory()->count(20)->create();

    // Act
    $response = actingAs($user)->get(route('users.index'));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->has('users.data', 15)
        ->has('users.links')
    );
});

it('allows combining search and role filters', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $user = User::factory()->manager()->create(['name' => 'Test User']);

    User::factory()->admin()->create(['name' => "{$uid} Admin"]);
    User::factory()->manager()->create(['name' => "{$uid} Manager"]);
    User::factory()->technician()->create(['name' => "{$uid} Technician"]);
    User::factory()->admin()->create(['name' => 'Jane Admin']);

    // Act
    $response = actingAs($user)->get(route('users.index', [
        'filter' => [
            'search' => $uid,
            'role' => 'admin',
        ],
    ]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->has('users.data', 1)
        ->where('filter.search', $uid)
        ->where('filter.role', 'admin')
    );
});

it('allows combining role and status filters', function () {
    // Arrange
    $user = User::factory()->manager()->create();

    User::factory()->admin()->active()->create(['name' => 'Active Admin']);
    User::factory()->admin()->suspended()->create(['name' => 'Suspended Admin']);
    User::factory()->manager()->active()->create(['name' => 'Active Manager']);
    User::factory()->manager()->suspended()->create(['name' => 'Suspended Manager']);

    // Act
    $response = actingAs($user)->get(route('users.index', [
        'filter' => [
            'role' => 'admin',
            'status' => 'active',
        ],
    ]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->has('users.data', 1)
        ->where('users.data.0.name', 'Active Admin')
        ->where('filter.role', 'admin')
        ->where('filter.status', 'active')
    );
});

it('allows filtering users by created_at with preset value (today)', function () {
    // Arrange
    $user = User::factory()->create(['created_at' => now()->subDays(5)]);

    User::factory()->create(['created_at' => now()->subDays(3)]);
    User::factory()->create(['created_at' => now()->subDays(1)]);
    $todayUser = User::factory()->create(['created_at' => now()]);

    // Act
    $response = actingAs($user)->get(route('users.index', [
        'filter' => ['created_at' => 'today'],
    ]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->has('users.data', 1)
        ->where('filter.created_at', 'today')
    );
});

it('allows filtering users by created_at with preset value (last_week)', function () {
    // Arrange
    $user = User::factory()->create(['created_at' => now()->subWeeks(2)]);

    User::factory()->create(['created_at' => now()->subWeeks(2)]);
    $lastWeek1 = User::factory()->create(['created_at' => now()->subDays(5)]);
    $lastWeek2 = User::factory()->create(['created_at' => now()->subDays(3)]);
    $today = User::factory()->create(['created_at' => now()]);

    // Act
    $response = actingAs($user)->get(route('users.index', [
        'filter' => ['created_at' => 'last_week'],
    ]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->has('users.data', 3)
        ->where('filter.created_at', 'last_week')
    );
});
