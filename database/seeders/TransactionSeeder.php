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
        // Create transactions for paid invoices
        Invoice::ofStatus(InvoiceStatus::Paid)->each(function (Invoice $invoice) {
            Transaction::factory()->forInvoice($invoice)->create([
                'amount' => $invoice->paid_amount,
            ]);
        });

        // Create transactions for refunded invoices
        Invoice::ofStatus(InvoiceStatus::Refunded)->each(function (Invoice $invoice) {
            Transaction::factory()->forInvoice($invoice)->create([
                'amount' => $invoice->refunded_amount,
            ]);
        });
    }
}
