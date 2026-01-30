<?php

namespace App\Actions\Orders;

use App\Models\Order;

class DeleteOrder
{
    public function handle(Order $order): void
    {
        $order->delete();
    }
}
