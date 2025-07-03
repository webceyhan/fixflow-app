<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create invoices for each ticket
        Ticket::all()->each(function ($ticket) {
            // Create an invoice factory for the ticket
            $invoiceFactory = Invoice::factory()->forTicket($ticket);

            // Create an invoice with various states
            match (rand(1, 4)) {
                1 => $invoiceFactory->create(),
                2 => $invoiceFactory->paid()->create(),
                3 => $invoiceFactory->refunded()->create(),
                4 => $invoiceFactory->overdue()->create(),
            };
        });
    }
}
