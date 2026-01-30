<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 0-2 orders for each ticket
        Ticket::all()->each(function (Ticket $ticket) {
            Order::factory()
                ->count(fake()->numberBetween(0, 2))
                ->forTicket($ticket)
                ->create();
        });

        // Create some received orders
        Ticket::all()->random(10)->each(function (Ticket $ticket) {
            Order::factory()
                ->forTicket($ticket)
                ->received()
                ->create();
        });

        // Create some cancelled orders
        Ticket::all()->random(3)->each(function (Ticket $ticket) {
            Order::factory()
                ->forTicket($ticket)
                ->cancelled()
                ->create();
        });
    }
}
