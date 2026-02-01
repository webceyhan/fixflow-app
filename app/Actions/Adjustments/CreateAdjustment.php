<?php

namespace App\Actions\Adjustments;

use App\Actions\Invoices\SyncInvoiceAdjustments;
use App\Enums\AdjustmentReason;
use App\Models\Adjustment;

class CreateAdjustment
{
    public function __construct(
        protected SyncInvoiceAdjustments $syncInvoiceAdjustments,
    ) {}

    public function handle(array $data): Adjustment
    {
        $reason = AdjustmentReason::from($data['reason']);

        $adjustment = Adjustment::create([
            'invoice_id' => $data['invoice_id'],
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
