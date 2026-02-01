<?php

namespace App\Actions\Invoices;

use App\Enums\AdjustmentType;
use App\Models\Invoice;

class SyncInvoiceAdjustments
{
    public function handle(Invoice $invoice): void
    {
        $invoice->forceFill([
            'discount_amount' => $invoice->adjustments()
                ->where('type', AdjustmentType::Discount)
                ->get()
                ->sum(fn ($adj) => $this->calculateAmount($adj, $invoice)),
            'fee_amount' => $invoice->adjustments()
                ->where('type', AdjustmentType::Fee)
                ->get()
                ->sum(fn ($adj) => $this->calculateAmount($adj, $invoice)),
        ])->save();
    }

    /**
     * Calculate the actual amount for an adjustment based on whether it's percentage-based.
     */
    protected function calculateAmount($adjustment, Invoice $invoice): float
    {
        if ($adjustment->is_percent) {
            $baseAmount = $invoice->task_total + $invoice->order_total;

            return ($baseAmount * $adjustment->amount) / 100;
        }

        return $adjustment->amount;
    }
}
