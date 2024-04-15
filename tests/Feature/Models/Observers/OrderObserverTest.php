<?php

use App\Models\Order;
use App\Models\Ticket;

it('can update ticket total_cost on all events', function () {
    $ticket = Ticket::factory()->create();
    $order = Order::factory()->forTicket($ticket)->create();

    // ignore non-billable orders
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
