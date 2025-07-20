<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $order->load('ticket.invoice');
        $order->ticket->fillOrderCounts()->save();

        // Update invoice order total if invoice exists
        $order->ticket->invoice?->fillOrderTotal()->save();
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Update counts if status changed
        if ($order->wasChanged(['status'])) {
            $order->load('ticket');
            $order->ticket->fillOrderCounts()->save();
        }

        // Update invoice subtotal if cost or billable status changed
        if ($order->wasChanged(['cost', 'is_billable'])) {
            $order->load('ticket.invoice');
            $order->ticket->invoice?->fillOrderTotal()->save();
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        $order->load('ticket.invoice');
        $order->ticket->fillOrderCounts()->save();

        // Update invoice total if invoice exists
        $order->ticket->invoice?->fillOrderTotal()->save();
    }
}
