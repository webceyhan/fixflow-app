<?php

namespace App\Observers;

use App\Models\Invoice;

class InvoiceObserver
{
    /**
     * Handle the Invoice "updated" event.
     *
     * Automatically recalculates percentage-based adjustments when subtotal changes.
     * This ensures adjustment amounts stay in sync when task_total or order_total change.
     */
    public function updated(Invoice $invoice): void
    {
        // Check if subtotal components have changed
        if ($invoice->wasChanged(['task_total', 'order_total'])) {
            // Load adjustments relationship to prevent N+1 queries
            $invoice->load('adjustments');

            // Only recalculate if there are percentage-based adjustments to sync
            if ($invoice->hasPercentageAdjustments()) {
                $invoice->fillAdjustmentAmounts()->saveQuietly();
            }
        }
    }
}
