<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Invoice::all()->random(10)->each(function (Invoice $invoice) {
            Transaction::factory()->forInvoice($invoice)->create();
        });
    }
}
