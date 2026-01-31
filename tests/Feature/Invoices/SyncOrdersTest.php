<?php

use App\Actions\Invoices\SyncInvoiceOrders;
use App\Models\Invoice;
use App\Models\Order;

it('syncs order_total when orders are added', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['order_total' => 0]);
    $ticket = $invoice->ticket;

    // Act - Create billable orders
    Order::factory()->billable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 200,
    ]);
    Order::factory()->billable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 100,
    ]);

    app(SyncInvoiceOrders::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->order_total)->toBe(300.0);
});

it('syncs order_total when orders are updated', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['order_total' => 200]);
    $ticket = $invoice->ticket;
    $order = Order::factory()->billable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 200,
    ]);

    // Act - Update order cost
    $order->update(['cost' => 350]);
    app(SyncInvoiceOrders::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->order_total)->toBe(350.0);
});

it('syncs order_total when orders are deleted', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['order_total' => 300]);
    $ticket = $invoice->ticket;
    $order1 = Order::factory()->billable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 200,
    ]);
    $order2 = Order::factory()->billable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 100,
    ]);

    // Act - Delete one order
    $order1->delete();
    app(SyncInvoiceOrders::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->order_total)->toBe(100.0);
});

it('only counts billable orders in order_total', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['order_total' => 0]);
    $ticket = $invoice->ticket;

    // Act - Create mix of billable and non-billable orders
    Order::factory()->billable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 200,
    ]);
    Order::factory()->notBillable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 100,
    ]);

    app(SyncInvoiceOrders::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->order_total)->toBe(200.0);
});

it('handles null invoice gracefully', function () {
    // Arrange & Act & Assert - Should not throw error
    app(SyncInvoiceOrders::class)->handle(null);
    expect(true)->toBeTrue();
});

it('calculates order_total as zero when no billable orders exist', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['order_total' => 500]);
    $ticket = $invoice->ticket;

    // Create only non-billable orders
    Order::factory()->notBillable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 100,
    ]);

    // Act
    app(SyncInvoiceOrders::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->order_total)->toBe(0.0);
});
