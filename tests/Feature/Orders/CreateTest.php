<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the create order form', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();

    // Act
    $response = actingAs($manager)->get("/tickets/{$ticket->id}/orders/create");

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->component('orders/Create'));
});

it('validates required fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();

    // Act
    $response = actingAs($manager)->post("/tickets/{$ticket->id}/orders", []);

    // Assert
    $response->assertSessionHasErrors(['name', 'quantity', 'cost']);
});

it('returns 404 when ticket does not exist', function () {
    // Arrange
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/tickets/9999/orders', [
        'name' => 'Replacement Screen',
        'quantity' => 1,
        'cost' => 100,
        'is_billable' => true,
    ]);

    // Assert
    $response->assertNotFound();
});

it('validates assignee exists', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();

    // Act
    $response = actingAs($manager)->post("/tickets/{$ticket->id}/orders", [
        'name' => 'Replacement Screen',
        'quantity' => 1,
        'cost' => 100,
        'is_billable' => true,
        'assignee_id' => 9999,
    ]);

    // Assert
    $response->assertSessionHasErrors(['assignee_id']);
});

it('validates cost is numeric and minimum 0', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();

    // Act
    $response = actingAs($manager)->post("/tickets/{$ticket->id}/orders", [
        'name' => 'Replacement Screen',
        'quantity' => 1,
        'cost' => -10,
        'is_billable' => true,
    ]);

    // Assert
    $response->assertSessionHasErrors(['cost']);
});

it('validates quantity is integer and minimum 1', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();

    // Act
    $response = actingAs($manager)->post("/tickets/{$ticket->id}/orders", [
        'name' => 'Replacement Screen',
        'quantity' => 0,
        'cost' => 100,
        'is_billable' => true,
    ]);

    // Assert
    $response->assertSessionHasErrors(['quantity']);
});

it('creates an order successfully with all fields', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    $assignee = User::factory()->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/tickets/{$ticket->id}/orders", [
        'name' => 'Replacement LCD Screen',
        'supplier' => 'TechParts Inc',
        'quantity' => 2,
        'cost' => 150.50,
        'is_billable' => true,
        'assignee_id' => $assignee->id,
        'note' => 'Urgent order for customer repair',
        'status' => OrderStatus::Created->value,
        'due_date' => now()->addDays(7)->format('Y-m-d H:i:s'),
    ]);

    // Assert
    $response->assertRedirect();
    expect(Order::count())->toBe(1);

    $order = Order::first();
    expect($order)
        ->ticket_id->toBe($ticket->id)
        ->name->toBe('Replacement LCD Screen')
        ->supplier->toBe('TechParts Inc')
        ->quantity->toBe(2)
        ->cost->toBe(150.50)
        ->is_billable->toBeTrue()
        ->assignee_id->toBe($assignee->id)
        ->note->toBe('Urgent order for customer repair')
        ->status->toBe(OrderStatus::Created)
        ->due_date->not->toBeNull();
});

it('creates an order with minimal required fields', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/tickets/{$ticket->id}/orders", [
        'name' => 'Basic Part',
        'quantity' => 1,
        'status' => OrderStatus::Created->value,
        'cost' => 0,
        'is_billable' => false,
    ]);

    // Assert
    $response->assertRedirect();
    expect(Order::count())->toBe(1);

    $order = Order::first();
    expect($order)
        ->ticket_id->toBe($ticket->id)
        ->name->toBe('Basic Part')
        ->supplier->toBeNull()
        ->quantity->toBe(1)
        ->cost->toBe(0.0)
        ->is_billable->toBeFalse()
        ->assignee_id->toBeNull()
        ->note->toBeNull()
        ->due_date->toBeNull();
});
