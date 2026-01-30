<?php

namespace App\Actions\Orders;

use App\Models\Order;

class UpdateOrder
{
    public function handle(Order $order, array $data): Order
    {
        $order->update([
            'assignee_id' => $data['assignee_id'] ?? null,
            'name' => $data['name'],
            'url' => $data['url'] ?? null,
            'supplier' => $data['supplier'] ?? null,
            'quantity' => $data['quantity'],
            'cost' => $data['cost'],
            'is_billable' => $data['is_billable'],
            'due_date' => $data['due_date'] ?? null,
            'note' => $data['note'] ?? null,
            'status' => $data['status'],
            'approved_at' => $data['approved_at'] ?? null,
        ]);

        return $order;
    }
}
