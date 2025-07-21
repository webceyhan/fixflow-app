<?php

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Ticket;
use App\Observers\OrderObserver;

beforeEach(function () {
    $this->observer = new OrderObserver;

    // Helpers

    $this->mockOrder = function (bool $syncTicket = false, bool $syncInvoice = false) {
        $order = mock(Order::class);
        $ticket = mock(Ticket::class);

        if ($syncTicket) {
            $ticket->shouldReceive('fillOrderCounts')->once()->andReturnSelf();
            $ticket->shouldReceive('save')->once()->andReturn(true);
        }

        if ($syncInvoice) {
            $invoice = mock(Invoice::class);
            $invoice->shouldReceive('fillOrderTotal')->once()->andReturnSelf();
            $invoice->shouldReceive('save')->once()->andReturn(true);

            $ticket->shouldReceive('getAttribute')->with('invoice')->andReturn($invoice);
        }

        $order->shouldReceive('load')->with('ticket')->andReturnSelf();
        $order->shouldReceive('load')->with('ticket.invoice')->andReturnSelf();
        $order->shouldReceive('getAttribute')->with('ticket')->andReturn($ticket);

        return $order;
    };

    $this->mockOrderWithUpdates = function (bool $statusChanged = false, bool $costOrBillableChanged = false) {
        $order = $this->mockOrder(syncTicket: $statusChanged, syncInvoice: $costOrBillableChanged);

        $order->shouldReceive('wasChanged')
            ->once()
            ->with(['status'])
            ->andReturn($statusChanged);

        $order->shouldReceive('wasChanged')
            ->once()
            ->with(['cost', 'is_billable'])
            ->andReturn($costOrBillableChanged);

        return $order;
    };
});

it('updates ticket order-counts and invoice order-total on creation', function () {
    // Arrange
    $order = $this->mockOrder(syncTicket: true, syncInvoice: true);

    // Act
    $this->observer->created($order);
});

it('does nothing when no relevant fields were changed', function () {
    // Arrange
    $order = $this->mockOrderWithUpdates();

    // Act
    $this->observer->updated($order);
});

it('updates ticket order-counts when status was changed', function () {
    // Arrange
    $order = $this->mockOrderWithUpdates(statusChanged: true);

    // Act
    $this->observer->updated($order);
});

it('updates invoice order-total when cost or billable was changed', function () {
    // Arrange
    $order = $this->mockOrderWithUpdates(costOrBillableChanged: true);

    // Act
    $this->observer->updated($order);
});

it('updates both ticket order-counts and invoice order-total when status and cost or billable changed', function () {
    // Arrange
    $order = $this->mockOrderWithUpdates(statusChanged: true, costOrBillableChanged: true);

    // Act
    $this->observer->updated($order);
});

it('updates ticket order-counts and invoice order-total on deletion', function () {
    // Arrange
    $order = $this->mockOrder(syncTicket: true, syncInvoice: true);

    // Act
    $this->observer->deleted($order);
});
