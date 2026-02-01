<?php

namespace App\Actions\Adjustments;

use App\Actions\Invoices\SyncInvoiceAdjustments;
use App\Enums\AdjustmentReason;
use App\Models\Adjustment;

class UpdateAdjustment
{
    public function __construct(
        protected SyncInvoiceAdjustments $syncInvoiceAdjustments,
    ) {}

    public function handle(Adjustment $adjustment, array $data): Adjustment
    {
        $reason = AdjustmentReason::from($data['reason']);

        $adjustment->update([
            'amount' => $data['amount'],
            'is_percent' => $data['is_percent'] ?? false,
            'note' => $data['note'] ?? null,
            'reason' => $reason,
            'type' => $reason->type(),
        ]);

        // Sync invoice discount/fee amounts
        $this->syncInvoiceAdjustments->handle($adjustment->invoice);

        return $adjustment;
    }
}
