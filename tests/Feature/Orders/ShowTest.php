<?php

use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the order show page', function () {
    // Arrange
    $viewer = User::factory()->create();
    $ticket = Ticket::factory()->create();
    $order = Order::factory()->forTicket($ticket)->create();

    // Act
    $response = actingAs($viewer)->get(route('orders.show', $order));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('orders/Show')
        ->has('order')
        ->where('order.id', $order->id)
        ->where('order.status', $order->status->value)
    );
});

it('loads order with ticket relationship', function () {
    // Arrange
    $viewer = User::factory()->create();
    $ticket = Ticket::factory()->create();
    $order = Order::factory()->forTicket($ticket)->create();

    // Act
    $response = actingAs($viewer)->get(route('orders.show', $order));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('order.ticket')
        ->where('order.ticket.id', $ticket->id)
    );
});

it('loads order with assignee relationship', function () {
    // Arrange
    $viewer = User::factory()->create();
    $assignee = User::factory()->create();
    $ticket = Ticket::factory()->create();
    $order = Order::factory()->forTicket($ticket)->create(['assignee_id' => $assignee->id]);

    // Act
    $response = actingAs($viewer)->get(route('orders.show', $order));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('order.assignee')
        ->where('order.assignee.id', $assignee->id)
    );
});
