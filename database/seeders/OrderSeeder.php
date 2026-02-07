<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Ticket;
use Database\Fixtures\OrderFixture;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 1-2 orders for 40% of tickets with specific parts/suppliers
        Ticket::all()
            ->random((int) Ticket::count() * 0.4)
            ->each(function (Ticket $ticket) {
                Order::factory(rand(1, 2))
                    ->forTicket($ticket)
                    ->state(OrderFixture::partsAndSupplier())
                    ->create();
            });

        // Mark 30% of orders as shipped
        Order::all()
            ->random((int) Order::count() * 0.3)
            ->each(fn (Order $order) => $order->update([
                'status' => OrderStatus::Shipped,
            ]));

        // Mark 50% of orders as received
        Order::all()
            ->random((int) Order::count() * 0.5)
            ->each(fn (Order $order) => $order->update([
                'status' => OrderStatus::Received,
            ]));

        // Mark 10% of orders as cancelled
        Order::all()
            ->random((int) Order::count() * 0.1)
            ->each(fn (Order $order) => $order->update([
                'status' => OrderStatus::Cancelled,
            ]));
    }
}
