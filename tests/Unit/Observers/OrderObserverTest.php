<?php

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Ticket;
use App\Observers\OrderObserver;

beforeEach(function () {
    $this->observer = new OrderObserver;
});

it('updates ticket order-counts and invoice order total on creation', function () {
    // Arrange
    $order = mock(Order::class);
    $ticket = mock(Ticket::class);
    $invoice = mock(Invoice::class);

    $order->shouldReceive('load')
        ->once()
        ->with('ticket.invoice')
        ->andReturnSelf();

    // The observer accesses $order->ticket twice
    $order->shouldReceive('getAttribute')
        ->with('ticket')
        ->twice()
        ->andReturn($ticket);

    $ticket->shouldReceive('fillOrderCounts')
        ->once()
        ->andReturnSelf();

    $ticket->shouldReceive('save')
        ->once()
        ->andReturn(true);

    // Mock invoice relationship access
    $ticket->shouldReceive('getAttribute')
        ->with('invoice')
        ->once()
        ->andReturn($invoice);

    $invoice->shouldReceive('fillOrderTotal')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('save')
        ->once()
        ->andReturn(true);

    // Act
    $this->observer->created($order);
});

it('does nothing when no relevant fields were changed', function () {
    // Arrange
    $order = mock(Order::class);

    $order->shouldReceive('wasChanged')
        ->once()
        ->with(['status'])
        ->andReturn(false);

    $order->shouldReceive('wasChanged')
        ->once()
        ->with(['cost', 'is_billable'])
        ->andReturn(false);

    // ticket should not be loaded or modified when no relevant changes
    $order->shouldNotReceive('load');

    // Act
    $this->observer->updated($order);
});

it('updates ticket order-counts when status was changed', function () {
    // Arrange
    $order = mock(Order::class);
    $ticket = mock(Ticket::class);

    $order->shouldReceive('wasChanged')
        ->once()
        ->with(['status'])
        ->andReturn(true);

    $order->shouldReceive('wasChanged')
        ->once()
        ->with(['cost', 'is_billable'])
        ->andReturn(false);

    $order->shouldReceive('load')
        ->once()
        ->with('ticket')
        ->andReturnSelf();

    $order->shouldReceive('getAttribute')
        ->with('ticket')
        ->once()
        ->andReturn($ticket);

    $ticket->shouldReceive('fillOrderCounts')
        ->once()
        ->andReturnSelf();

    $ticket->shouldReceive('save')
        ->once()
        ->andReturn(true);

    $this->observer->updated($order);
});

it('updates invoice order total when cost or billable status changed', function () {
    // Arrange
    $order = mock(Order::class);
    $ticket = mock(Ticket::class);
    $invoice = mock(Invoice::class);

    $order->shouldReceive('wasChanged')
        ->once()
        ->with(['status'])
        ->andReturn(false);

    $order->shouldReceive('wasChanged')
        ->once()
        ->with(['cost', 'is_billable'])
        ->andReturn(true);

    $order->shouldReceive('load')
        ->once()
        ->with('ticket.invoice')
        ->andReturnSelf();

    $order->shouldReceive('getAttribute')
        ->with('ticket')
        ->once()
        ->andReturn($ticket);

    $ticket->shouldReceive('getAttribute')
        ->with('invoice')
        ->once()
        ->andReturn($invoice);

    $invoice->shouldReceive('fillOrderTotal')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('save')
        ->once()
        ->andReturn(true);

    $this->observer->updated($order);
});

it('updates both ticket counts and invoice total when both status and cost changed', function () {
    // Arrange
    $order = mock(Order::class);
    $ticket = mock(Ticket::class);
    $invoice = mock(Invoice::class);

    $order->shouldReceive('wasChanged')
        ->once()
        ->with(['status'])
        ->andReturn(true);

    $order->shouldReceive('wasChanged')
        ->once()
        ->with(['cost', 'is_billable'])
        ->andReturn(true);

    // First load for status change
    $order->shouldReceive('load')
        ->once()
        ->with('ticket')
        ->andReturnSelf();

    // Second load for cost/billable change
    $order->shouldReceive('load')
        ->once()
        ->with('ticket.invoice')
        ->andReturnSelf();

    $order->shouldReceive('getAttribute')
        ->with('ticket')
        ->twice()
        ->andReturn($ticket);

    $ticket->shouldReceive('fillOrderCounts')
        ->once()
        ->andReturnSelf();

    $ticket->shouldReceive('save')
        ->once()
        ->andReturn(true);

    $ticket->shouldReceive('getAttribute')
        ->with('invoice')
        ->once()
        ->andReturn($invoice);

    $invoice->shouldReceive('fillOrderTotal')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('save')
        ->once()
        ->andReturn(true);

    $this->observer->updated($order);
});

it('updates ticket order-counts and invoice order total on deletion', function () {
    // Arrange
    $order = mock(Order::class);
    $ticket = mock(Ticket::class);
    $invoice = mock(Invoice::class);

    $order->shouldReceive('load')
        ->once()
        ->with('ticket.invoice')
        ->andReturnSelf();

    // The observer accesses $order->ticket twice
    $order->shouldReceive('getAttribute')
        ->with('ticket')
        ->twice()
        ->andReturn($ticket);

    $ticket->shouldReceive('fillOrderCounts')
        ->once()
        ->andReturnSelf();

    $ticket->shouldReceive('save')
        ->once()
        ->andReturn(true);

    // Mock invoice relationship access
    $ticket->shouldReceive('getAttribute')
        ->with('invoice')
        ->once()
        ->andReturn($invoice);

    $invoice->shouldReceive('fillOrderTotal')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('save')
        ->once()
        ->andReturn(true);

    // Act
    $this->observer->deleted($order);
});
