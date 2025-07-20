<?php

namespace Database\Seeders;

use App\Enums\AdjustmentReason;
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
        // Create adjustments for pending invoices
        Invoice::pending()->each(function (Invoice $invoice) {

            // Create an adjustment factory for the invoice
            $adjustmentFactory = Adjustment::factory()->forInvoice($invoice);

            // Create a percentage based adjustment with various states
            match (rand(1, 6)) {
                // bonus
                1 => $adjustmentFactory->ofReason(AdjustmentReason::Welcome)->create(),
                // discount
                2 => $adjustmentFactory->ofReason(AdjustmentReason::Promotion)->create(),
                // fee
                3 => $adjustmentFactory->ofReason(AdjustmentReason::RushService)->create(),
                // compensation
                4 => $adjustmentFactory->ofReason(AdjustmentReason::ServiceDelay)->create(),
                // fixed amount bonus
                5 => $adjustmentFactory->ofReason(AdjustmentReason::Loyalty)->withAmount(10)->create(),
                // fixed amount fee
                6 => $adjustmentFactory->ofReason(AdjustmentReason::Service)->withAmount(50)->create(),
            };
        });
    }
}
