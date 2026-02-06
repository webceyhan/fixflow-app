<?php

use App\Models\Customer;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the customers list to authenticated users', function () {
    // Arrange
    $viewer = User::factory()->create();
    $customers = Customer::factory()->count(3)->create();

    // Act
    $response = actingAs($viewer)->get(route('customers.index'));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('customers/Index')
        ->has('customers.data', 3)
        ->has('customers.links')
        ->has('filter')
    );
});

it('allows searching customers by name', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $viewer = User::factory()->create();
    Customer::factory()->create(['name' => "John {$uid} Doe"]);
    Customer::factory()->create(['name' => 'Jane Smith']);
    Customer::factory()->create(['name' => "Bob {$uid} Johnson"]);

    // Act
    $response = actingAs($viewer)->get(route('customers.index', ['filter' => ['search' => $uid]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('customers/Index')
        ->has('customers.data', 2)
    );
});

it('allows searching customers by email', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $viewer = User::factory()->create();
    Customer::factory()->create(['email' => "john{$uid}@example.com"]);
    Customer::factory()->create(['email' => "jane{$uid}@example.com"]);
    Customer::factory()->create(['email' => 'bob@test.com']);

    // Act
    $response = actingAs($viewer)->get(route('customers.index', ['filter' => ['search' => $uid]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('customers/Index')
        ->has('customers.data', 2)
    );
});

it('allows searching customers by company', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $viewer = User::factory()->create();
    Customer::factory()->business("Acme {$uid} Corp")->create();
    Customer::factory()->business('Tech Solutions')->create();
    Customer::factory()->individual()->create();

    // Act
    $response = actingAs($viewer)->get(route('customers.index', ['filter' => ['search' => $uid]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('customers/Index')
        ->has('customers.data', 1)
    );
});

it('allows filtering customers by type', function () {
    // Arrange
    $viewer = User::factory()->create();
    Customer::factory()->individual()->count(2)->create();
    Customer::factory()->business()->count(3)->create();

    // Act
    $response = actingAs($viewer)->get(route('customers.index', ['filter' => ['type' => 'individual']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('customers/Index')
        ->has('customers.data', 2)
    );
});

it('allows filtering customers by status', function () {
    // Arrange
    $viewer = User::factory()->create();
    Customer::factory()->active()->count(2)->create();
    Customer::factory()->suspended()->count(3)->create();

    // Act
    $response = actingAs($viewer)->get(route('customers.index', ['filter' => ['status' => 'active']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('customers/Index')
        ->has('customers.data', 2)
    );
});

it('paginates customers correctly', function () {
    // Arrange
    $viewer = User::factory()->create();
    Customer::factory()->count(20)->create();

    // Act
    $response = actingAs($viewer)->get(route('customers.index'));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('customers/Index')
        ->has('customers.data', 10)
        ->has('customers.links')
    );
});

it('allows combining search and type filters', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $viewer = User::factory()->create();
    Customer::factory()->individual()->create(['name' => "John {$uid} Individual"]);
    Customer::factory()->business()->create(['name' => "John {$uid} Business"]);
    Customer::factory()->individual()->create(['name' => 'Jane Individual']);

    // Act
    $response = actingAs($viewer)->get(route('customers.index', [
        'filter' => [
            'search' => $uid,
            'type' => 'individual',
        ],
    ]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('customers/Index')
        ->has('customers.data', 1)
        ->where('filter.search', $uid)
        ->where('filter.type', 'individual')
    );
});

it('allows combining type and status filters', function () {
    // Arrange
    $viewer = User::factory()->create();
    Customer::factory()->business()->active()->count(2)->create();
    Customer::factory()->business()->suspended()->count(3)->create();
    Customer::factory()->individual()->active()->count(1)->create();

    // Act
    $response = actingAs($viewer)->get(route('customers.index', [
        'filter' => [
            'type' => 'business',
            'status' => 'active',
        ],
    ]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('customers/Index')
        ->has('customers.data', 2)
        ->where('filter.type', 'business')
        ->where('filter.status', 'active')
    );
});
