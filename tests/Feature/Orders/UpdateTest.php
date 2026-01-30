<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the edit order form', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $order = Order::factory()->forTicket($ticket)->create();

    // Act
    $response = actingAs($manager)->get(route('orders.edit', $order));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('orders/Edit')
        ->has('order')
        ->where('order.id', $order->id)
    );
});

it('validates required fields on update', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $order = Order::factory()->create();

    // Act
    $response = actingAs($manager)->put(route('orders.update', $order), [
        'name' => null,
    ]);

    // Assert
    $response->assertSessionHasErrors(['name', 'quantity', 'cost', 'is_billable']);
});

it('does not allow changing ticket_id', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $order = Order::factory()->create();
    $originalTicketId = $order->ticket_id;

    // Act
    $response = actingAs($manager)->put(route('orders.update', $order), [
        'name' => 'Updated Part Name',
        'quantity' => 3,
        'cost' => 200,
        'is_billable' => true,
        'status' => OrderStatus::Shipped->value,
    ]);

    // Assert
    $response->assertRedirect();
    expect($order->fresh()->ticket_id)->toBe($originalTicketId);
});

it('validates assignee exists on update', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $order = Order::factory()->create();

    // Act
    $response = actingAs($manager)->put(route('orders.update', $order), [
        'name' => 'Updated Part',
        'quantity' => 1,
        'cost' => 100,
        'is_billable' => true,
        'assignee_id' => 9999,
        'status' => OrderStatus::Created->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['assignee_id']);
});

it('updates an order successfully with all fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $assignee = User::factory()->create();
    $ticket = Ticket::factory()->create();
    $order = Order::factory()->forTicket($ticket)->create([
        'name' => 'Original Part',
        'supplier' => 'Old Supplier',
        'quantity' => 1,
        'cost' => 50,
        'status' => OrderStatus::Created,
    ]);
    $originalTicketId = $order->ticket_id;

    // Act
    $response = actingAs($manager)->put(route('orders.update', $order), [
        'name' => 'Updated LCD Screen',
        'supplier' => 'New TechParts',
        'quantity' => 5,
        'cost' => 200.75,
        'is_billable' => true,
        'assignee_id' => $assignee->id,
        'note' => 'Updated order note',
        'status' => OrderStatus::Received->value,
        'due_date' => now()->addDays(14)->format('Y-m-d H:i:s'),
    ]);

    // Assert
    $response->assertRedirect(route('orders.show', $order));
    expect($order->fresh())
        ->ticket_id->toBe($originalTicketId)
        ->name->toBe('Updated LCD Screen')
        ->supplier->toBe('New TechParts')
        ->quantity->toBe(5)
        ->cost->toBe(200.75)
        ->is_billable->toBeTrue()
        ->assignee_id->toBe($assignee->id)
        ->note->toBe('Updated order note')
        ->status->toBe(OrderStatus::Received)
        ->due_date->not->toBeNull();
});

it('updates order and clears optional fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $assignee = User::factory()->create();
    $order = Order::factory()->create([
        'supplier' => 'Original Supplier',
        'assignee_id' => $assignee->id,
        'note' => 'Original note',
        'due_date' => now()->addDays(7),
    ]);

    // Act
    $response = actingAs($manager)->put(route('orders.update', $order), [
        'name' => 'Basic Part',
        'quantity' => 1,
        'cost' => 0,
        'is_billable' => false,
        'supplier' => null,
        'assignee_id' => null,
        'note' => null,
        'status' => OrderStatus::Created->value,
        'due_date' => null,
    ]);

    // Assert
    $response->assertRedirect();
    expect($order->fresh())
        ->supplier->toBeNull()
        ->assignee_id->toBeNull()
        ->note->toBeNull()
        ->due_date->toBeNull();
});
