<?php

namespace App\Actions\Orders;

use App\Actions\Invoices\SyncInvoiceOrders;
use App\Models\Order;

class CreateOrder
{
    public function __construct(
        protected SyncInvoiceOrders $syncInvoiceOrders,
    ) {}

    public function handle(array $data): Order
    {
        $order = Order::create([
            'ticket_id' => $data['ticket_id'],
            'assignee_id' => $data['assignee_id'] ?? null,
            'name' => $data['name'],
            'url' => $data['url'] ?? null,
            'supplier' => $data['supplier'] ?? null,
            'quantity' => $data['quantity'] ?? 1,
            'cost' => $data['cost'] ?? 0,
            'is_billable' => $data['is_billable'] ?? true,
            'due_date' => $data['due_date'] ?? null,
            'note' => $data['note'] ?? null,
            'status' => $data['status'] ?? null,
            'approved_at' => $data['approved_at'] ?? null,
        ]);

        // Sync order total to invoice
        $this->syncInvoiceOrders->handle($order->ticket->invoice);

        return $order;
    }
}
