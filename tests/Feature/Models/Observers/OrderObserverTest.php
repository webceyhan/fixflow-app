<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Ticket;

it('can save cancelled order as non-billable', function () {
    $order = Order::factory()->create();

    expect($order->is_billable)->toBeTrue();

    $order->cancel();
    $order->refresh();

    expect($order->is_billable)->toBeFalse();
});

it('can update ticket total_cost on all events', function () {
    $ticket = Ticket::factory()->create();
    $order = Order::factory()->forTicket($ticket)->create();

    // ignore cancelled, non-billable orders
    Order::factory()->forTicket($ticket)->cancelled()->create();
    Order::factory()->forTicket($ticket)->free()->create();

    $ticket->refresh();

    expect($ticket->total_cost)->toBe($order->cost);

    // update order cost
    $order->update(['cost' => 100.0]);
    $ticket->refresh();

    expect($ticket->total_cost)->toBe(100.0);

    // delete order
    $order->delete();
    $ticket->refresh();

    expect($ticket->total_cost)->toBe(0.0);
});

it('can update ticket order counters on all events', function () {
    $ticket = Ticket::factory()->create();
    $order = Order::factory()->forTicket($ticket)->create();
    Order::factory()->forTicket($ticket)->cancelled()->create();

    $ticket->refresh();

    expect($ticket->total_orders_count)->toBe(2);
    expect($ticket->pending_orders_count)->toBe(1);

    // update order status
    $order->update(['status' => OrderStatus::Received]);
    $ticket->refresh();

    expect($ticket->total_orders_count)->toBe(2);
    expect($ticket->pending_orders_count)->toBe(0);

    // delete order
    $order->delete();
    $ticket->refresh();

    expect($ticket->total_orders_count)->toBe(1);
    expect($ticket->pending_orders_count)->toBe(0);
});
