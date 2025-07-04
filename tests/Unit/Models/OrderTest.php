<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

it('creates a order with valid attributes', function () {
    // Arrange & Act
    $order = Order::factory()->create();

    // Assert
    expect($order->ticket_id)->not->toBeNull();
    expect($order->name)->not->toBeEmpty();
    expect($order->quantity)->toBeGreaterThan(0);
    expect($order->cost)->toBeGreaterThan(0);
    expect($order->is_billable)->toBeTrue();
    expect($order->status)->toBeInstanceOf(OrderStatus::class);
    expect($order->approved_at)->toBeInstanceOf(Carbon::class);
});

it('can create an order for a ticket', function () {
    // Arrange
    $ticket = Ticket::factory()->create();

    // Act
    $order = Order::factory()->forTicket($ticket)->create();

    // Assert
    expect($order->ticket->id)->toBe($ticket->id);
});

it('can create an order of status', function (OrderStatus $status) {
    // Arrange & Act
    $order = Order::factory()->ofStatus($status)->create();

    // Assert
    expect($order->status)->toBe($status);
})->with(OrderStatus::cases());

it('can update an order', function () {
    // Arrange
    $order = Order::factory()->create();

    // Act
    $order->update([
        'name' => 'Updated order name',
        'url' => 'https://example.com/updated-order',
        'supplier' => 'Updated order supplier',
        'quantity' => 2,
        'cost' => 200,
        'is_billable' => false,
        'status' => OrderStatus::Shipped,
        'approved_at' => now(),
    ]);

    // Assert
    expect($order->name)->toBe('Updated order name');
    expect($order->url)->toBe('https://example.com/updated-order');
    expect($order->supplier)->toBe('Updated order supplier');
    expect($order->quantity)->toEqual(2);
    expect($order->cost)->toEqual(200);
    expect($order->is_billable)->toBeFalse();
    expect($order->status)->toBe(OrderStatus::Shipped);
    expect($order->approved_at)->toBeInstanceOf(Carbon::class);
});

it('can delete an order', function () {
    // Arrange
    $order = Order::factory()->create();

    // Act
    $order->delete();

    // Assert
    expect(Order::find($order->id))->toBeNull();
});

it('belongs to a ticket', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    $order = Order::factory()->forTicket($ticket)->create();

    // Assert
    expect($order->ticket)->toBeInstanceOf(Ticket::class);
    expect($order->ticket->id)->toBe($ticket->id);
});

it('can filter orders by status scope', function (OrderStatus $status) {
    // Arrange
    Order::factory()->ofStatus($status)->create();

    // Act
    $orders = Order::query()->ofStatus($status)->get();

    // Assert
    expect($orders)->toHaveCount(1);
    expect($orders->first()->status)->toBe($status);
})->with(OrderStatus::cases());
