<?php

namespace Database\Seeders;

use App\Models\Adjustment;
use App\Models\Invoice;
use Illuminate\Database\Seeder;

class AdjustmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Invoice::all()->each(function (Invoice $invoice) {
            if (fake()->boolean(30)) {
                return; // 30% of invoices have no adjustments
            }

            // 50% chance to add a percentage-based discount first
            if (fake()->boolean(50)) {
                Adjustment::factory()->forInvoice($invoice)->discount()->percentage()->create();
            }

            // 20% chance to add a fixed fee
            if (fake()->boolean(20)) {
                Adjustment::factory()->forInvoice($invoice)->fee()->fixed()->create();
            }
        });
    }
}
