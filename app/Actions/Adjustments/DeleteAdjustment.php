<?php

namespace App\Actions\Adjustments;

use App\Actions\Invoices\SyncInvoiceAdjustments;
use App\Models\Adjustment;

class DeleteAdjustment
{
    public function __construct(
        protected SyncInvoiceAdjustments $syncInvoiceAdjustments,
    ) {}

    public function handle(Adjustment $adjustment): void
    {
        $invoice = $adjustment->invoice;

        $adjustment->delete();

        // Sync invoice discount/fee amounts
        $this->syncInvoiceAdjustments->handle($invoice);
    }
}
