<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ticket::all()->random(10)->each(function (Ticket $ticket) {
            Order::factory()->forTicket($ticket)->create();
        });
    }
}
