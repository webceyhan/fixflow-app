<?php

use App\Models\Order;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the orders list to authenticated users', function () {
    // Arrange
    $viewer = User::factory()->create();
    $orders = Order::factory()->count(3)->create();

    // Act
    $response = actingAs($viewer)->get(route('orders.index'));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('orders/Index')
        ->has('orders.data', 3)
        ->has('orders.links')
        ->has('filter')
    );
});

it('allows searching orders', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $viewer = User::factory()->create();
    Order::factory()->count(2)->create(['note' => "{$uid} Replacement part order"]);
    Order::factory()->create(['note' => 'Screen assembly order']);

    // Act
    $response = actingAs($viewer)->get(route('orders.index', ['filter' => ['search' => $uid]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('orders/Index')
        ->has('orders.data', 2)
    );
});

it('allows filtering orders by status', function () {
    // Arrange
    $viewer = User::factory()->create();
    Order::factory()->count(2)->create(['status' => 'created']);
    Order::factory()->count(3)->create(['status' => 'shipped']);

    // Act
    $response = actingAs($viewer)->get(route('orders.index', ['filter' => ['status' => 'created']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('orders/Index')
        ->has('orders.data', 2)
    );
});

it('allows filtering orders by assignee - me', function () {
    // Arrange
    $viewer = User::factory()->create();
    $otherUser = User::factory()->create();
    Order::factory()->count(2)->create(['assignee_id' => $viewer->id]);
    Order::factory()->count(3)->create(['assignee_id' => $otherUser->id]);

    // Act
    $response = actingAs($viewer)->get(route('orders.index', ['filter' => ['assignee' => 'me']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('orders/Index')
        ->has('orders.data', 2)
    );
});

it('allows filtering orders by assignee - none', function () {
    // Arrange
    $viewer = User::factory()->create();
    $assignee = User::factory()->create();
    Order::factory()->count(2)->create(['assignee_id' => null]);
    Order::factory()->count(3)->create(['assignee_id' => $assignee->id]);

    // Act
    $response = actingAs($viewer)->get(route('orders.index', ['filter' => ['assignee' => 'none']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('orders/Index')
        ->has('orders.data', 2)
    );
});

it('allows filtering orders by assignee - others', function () {
    // Arrange
    $viewer = User::factory()->create();
    $otherAssignee = User::factory()->create();
    Order::factory()->count(2)->create(['assignee_id' => $otherAssignee->id]);
    Order::factory()->count(3)->create(['assignee_id' => $viewer->id]);

    // Act
    $response = actingAs($viewer)->get(route('orders.index', ['filter' => ['assignee' => 'others']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('orders/Index')
        ->has('orders.data', 2)
    );
});

it('allows filtering orders by created date', function () {
    // Arrange
    $viewer = User::factory()->create();
    Order::factory()->count(2)->create(['created_at' => now()]);
    Order::factory()->count(3)->create(['created_at' => now()->subDays(10)]);

    // Act
    $response = actingAs($viewer)->get(route('orders.index', ['filter' => ['created_at' => 'today']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('orders/Index')
        ->has('orders.data', 2)
    );
});

it('allows filtering orders by due date', function () {
    // Arrange
    $viewer = User::factory()->create();
    Order::factory()->count(2)->create(['due_date' => now()]);
    Order::factory()->count(3)->create(['due_date' => now()->addDays(10)]);

    // Act
    $response = actingAs($viewer)->get(route('orders.index', ['filter' => ['due_date' => 'today']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('orders/Index')
        ->has('orders.data', 2)
    );
});
