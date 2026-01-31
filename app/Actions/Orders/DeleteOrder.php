<?php

namespace App\Actions\Orders;

use App\Actions\Invoices\SyncInvoiceOrders;
use App\Models\Order;

class DeleteOrder
{
    public function __construct(
        protected SyncInvoiceOrders $syncInvoiceOrders,
    ) {}

    public function handle(Order $order): void
    {
        $invoice = $order->ticket->invoice;

        $order->delete();

        // Sync order total to invoice
        $this->syncInvoiceOrders->handle($invoice);
    }
}
