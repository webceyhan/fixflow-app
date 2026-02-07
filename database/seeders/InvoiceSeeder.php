<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Ticket;
use Database\Fixtures\InvoiceFixture;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create draft invoice for all tickets with fixture notes
        Ticket::all()->each(function (Ticket $ticket) {
            Invoice::factory()
                ->forTicket($ticket)
                ->draft()
                ->withNote(InvoiceFixture::randomNote('draft'))
                ->create();
        });

        // Update some invoices to paid status
        $invoices = Invoice::all();
        $invoices->random(min(10, $invoices->count()))->each(function (Invoice $invoice) {
            $invoice->update([
                'status' => InvoiceStatus::Paid,
                'paid_amount' => $invoice->total,
                'note' => InvoiceFixture::randomNote('paid'),
            ]);
        });

        // Update some invoices to sent status
        $invoices = Invoice::ofStatus(InvoiceStatus::Draft)->get();
        $invoices->random(min(10, $invoices->count()))->each(function (Invoice $invoice) {
            $invoice->update([
                'status' => InvoiceStatus::Sent,
                'note' => InvoiceFixture::randomNote('sent'),
            ]);
        });

        // Update some invoices to cancelled status
        $invoices = Invoice::ofStatus(InvoiceStatus::Draft)->get();
        $invoices->random(min(5, $invoices->count()))->each(function (Invoice $invoice) {
            $invoice->update([
                'status' => InvoiceStatus::Cancelled,
                'note' => InvoiceFixture::randomNote('cancelled'),
            ]);
        });
    }
}
