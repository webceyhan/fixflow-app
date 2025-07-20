<?php

namespace App\Observers;

use App\Models\Adjustment;

class AdjustmentObserver
{
    /**
     * Handle the Adjustment "created" event.
     */
    public function created(Adjustment $adjustment): void
    {
        $adjustment->load('invoice');
        $adjustment->invoice->fillAdjustmentAmounts()->saveQuietly();
    }

    /**
     * Handle the Adjustment "updated" event.
     */
    public function updated(Adjustment $adjustment): void
    {
        // Only update if fields that affect calculation changed
        if ($adjustment->wasChanged(['amount', 'percentage'])) {
            $adjustment->load('invoice');
            $adjustment->invoice->fillAdjustmentAmounts()->saveQuietly();
        }
    }

    /**
     * Handle the Adjustment "deleted" event.
     */
    public function deleted(Adjustment $adjustment): void
    {
        $adjustment->load('invoice');
        $adjustment->invoice->fillAdjustmentAmounts()->saveQuietly();
    }
}
