<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a single payment transaction for paid invoices
        Invoice::ofStatus(InvoiceStatus::Paid)->each(function (Invoice $invoice) {
            Transaction::factory()
                ->forInvoice($invoice)
                ->payment($invoice->paid_amount)
                ->create();
        });

        // Create a deposit transaction for sent invoices (20% of total)
        Invoice::ofStatus(InvoiceStatus::Sent)->each(function (Invoice $invoice) {
            Transaction::factory()
                ->forInvoice($invoice)
                ->deposit(round($invoice->total * 0.2, 2))
                ->create();
        });

        // Create deposit and partial payment transactions of some draft invoices
        Invoice::ofStatus(InvoiceStatus::Draft)->get()->random(10)->each(function (Invoice $invoice) {
            Transaction::factory()
                ->forInvoice($invoice)
                ->deposit(round($invoice->total * 0.2, 2))
                ->create();

            Transaction::factory()
                ->forInvoice($invoice)
                ->payment($invoice->total - round($invoice->total * 0.3, 2))
                ->create();

            $invoice->update(['status' => InvoiceStatus::Issued]);
        });

        // Create payment and refund transactions of some draft invoices
        Invoice::ofStatus(InvoiceStatus::Draft)->get()->random(10)->each(function (Invoice $invoice) {
            Transaction::factory()
                ->forInvoice($invoice)
                ->payment($invoice->total)
                ->create();

            Transaction::factory()
                ->forInvoice($invoice)
                ->refund($invoice->total)
                ->create();

            $invoice->update(['status' => InvoiceStatus::Refunded]);
        });
    }
}
