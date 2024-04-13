<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ticket::all()->random(10)->each(function (Ticket $ticket) {
            Invoice::factory()->forTicket($ticket)->create();
        });
    }
}
