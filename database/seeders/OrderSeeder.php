<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Create a random number of orders for each ticket.
        Ticket::all()->random(30)->each(function (Ticket $ticket) {
            // Create normal order
            Order::factory()->forTicket($ticket)->create();

            // Create an order that needs approval
            Order::factory()->forTicket($ticket)->unapproved()->create();
        });

        // Mark some orders as non-billable
        Order::all()->random(5)->each(function (Order $order) {
            $order->is_billable = false;
            $order->save();
        });

        // Mark some orders as cancelled
        Order::all()->random(10)->each(function (Order $order) {
            $order->status = OrderStatus::Cancelled;
            $order->save();
        });

        // Mark some orders as shipped
        Order::ofStatus(OrderStatus::New)->get()->random(20)->each(function (Order $order) {
            $order->status = OrderStatus::Shipped;
            $order->save();
        });

        // Mark some shipped orders as received
        Order::ofStatus(OrderStatus::Shipped)->get()->random(10)->each(function (Order $order) {
            $order->status = OrderStatus::Received;
            $order->save();
        });
    }
}
