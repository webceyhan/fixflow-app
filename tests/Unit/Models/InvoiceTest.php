<?php

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates an invoice with valid attributes', function () {
    // Arrange & Act
    $invoice = Invoice::factory()->create();

    // Assert
    expect($invoice->ticket_id)->not->toBeNull();
    expect($invoice->total)->toBeGreaterThanOrEqual(0);
    expect($invoice->task_total)->toBeGreaterThanOrEqual(0);
    expect($invoice->order_total)->toBeGreaterThanOrEqual(0);
    expect($invoice->discount_amount)->toBeGreaterThanOrEqual(0);
    expect($invoice->paid_amount)->toBeGreaterThanOrEqual(0);
    expect($invoice->refunded_amount)->toBeGreaterThanOrEqual(0);
    expect($invoice->due_date)->not->toBeNull();
    expect($invoice->status)->toBeInstanceOf(InvoiceStatus::class);
});

it('can create an invoice for a ticket', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    // Assert
    expect($invoice->ticket->id)->toBe($ticket->id);
});

it('can create a paid invoice', function () {
    // Arrange
    $invoice = Invoice::factory()->paid()->create();

    // Assert
    expect($invoice->status)->toBe(InvoiceStatus::Paid);
    expect($invoice->paid_amount)->toBe($invoice->total);
});

it('can create a refunded invoice', function () {
    // Arrange
    $invoice = Invoice::factory()->refunded()->create();

    // Assert
    expect($invoice->status)->toBe(InvoiceStatus::Refunded);
    expect($invoice->refunded_amount)->toBe($invoice->total);
});

it('can update an invoice', function () {
    // Arrange
    $invoice = Invoice::factory()->create();

    // Act
    $invoice->update([
        'total' => 180.0,
        'task_total' => 200.0,
        'order_total' => 50.0,
        'discount_amount' => 20.0,
        'paid_amount' => 100.0,
        'refunded_amount' => 0.0,
        'status' => InvoiceStatus::Sent,
    ]);

    // Assert
    expect($invoice->total)->toBe(180.0);
    expect($invoice->task_total)->toBe(200.0);
    expect($invoice->order_total)->toBe(50.0);
    expect($invoice->discount_amount)->toBe(20.0);
    expect($invoice->paid_amount)->toBe(100.0);
    expect($invoice->refunded_amount)->toBe(0.0);
    expect($invoice->status)->toBe(InvoiceStatus::Sent);
});

it('can delete an invoice', function () {
    // Arrange
    $invoice = Invoice::factory()->create();

    // Act
    $invoice->delete();

    // Assert
    expect(Invoice::find($invoice->id))->toBeNull();
});

it('belongs to a ticket', function () {
    $ticket = Ticket::factory()->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    expect($invoice->ticket_id)->toBe($ticket->id);
    expect($invoice->ticket->id)->toBe($ticket->id);
});

it('belongs to a device via ticket', function () {
    $device = Device::factory()->create();
    $ticket = Ticket::factory()->forDevice($device)->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    expect($invoice->ticket_id)->toBe($ticket->id);
    expect($ticket->device->id)->toBe($device->id);
});

it('belongs to a customer via device', function () {
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();
    $ticket = Ticket::factory()->forDevice($device)->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    expect($invoice->ticket_id)->toBe($ticket->id);
    expect($invoice->customer->id)->toBe($customer->id);
});

// TASK AND ORDER TOTALS ///////////////////////////////////////////////////////////////////////////

it('fills task total correctly with billable tasks', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    Task::factory()->forTicket($ticket)->billable(100.00)->create();
    Task::factory()->forTicket($ticket)->billable(75.25)->create();
    Task::factory()->forTicket($ticket)->notBillable(200.00)->create(); // ignored

    // Act
    $invoice->fillTaskTotal();

    // Assert
    expect($invoice->task_total)->toBe(175.25); // 100 + 75.25
});

it('fills order total correctly with billable orders', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    Order::factory()->forTicket($ticket)->billable(200.00)->create();
    Order::factory()->forTicket($ticket)->billable(50.25)->create();
    Order::factory()->forTicket($ticket)->notBillable()->create(['cost' => 300.00]); // ignored

    // Act
    $invoice->fillOrderTotal();

    // Assert
    expect($invoice->order_total)->toBe(250.25); // 200 + 50.25
});

// FINANCIAL CALCULATIONS //////////////////////////////////////////////////////////////////////////

it('fills financials correctly with no transactions', function () {
    // Arrange
    $invoice = Invoice::factory()->pending()->create();

    // Assert
    expect($invoice->fillTransactionAmounts())
        ->paid_amount->toBe(0.0)
        ->refunded_amount->toBe(0.0);
});

it('fills financials correctly with payment transactions', function (float $amount) {
    // Arrange
    $invoice = Invoice::factory()->pending()->create([
        'total' => 100.0,
    ]);

    // Create payment transaction
    Transaction::factory()->forInvoice($invoice)->payment($amount)->create();

    // Assert
    expect($invoice->fillTransactionAmounts())
        ->paid_amount->toBe($amount)
        ->refunded_amount->toBe(0.0);
})->with([
    'partial payment' => [50.0],
    'full payment' => [100.0],
    'overpayment' => [120.0],
]);

it('fills financials correctly with refund transactions', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'total' => 100.0,
        'status' => InvoiceStatus::Paid,
    ]);

    // Create payment and refund transactions
    Transaction::factory()->forInvoice($invoice)->payment(100.0)->create();
    Transaction::factory()->forInvoice($invoice)->refund(30.0)->create();

    // Assert
    expect($invoice->fillTransactionAmounts())
        ->paid_amount->toBe(100.0)
        ->refunded_amount->toBe(30.0);
});

it('aggregates multiple transactions correctly', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'total' => 100.0,
        'status' => InvoiceStatus::Draft,
    ]);

    // Create multiple payment transactions
    Transaction::factory()->forInvoice($invoice)->payment(30.0)->create();
    Transaction::factory()->forInvoice($invoice)->payment(40.0)->create();
    Transaction::factory()->forInvoice($invoice)->refund(10.0)->create();

    // Assert
    expect($invoice->fillTransactionAmounts())
        ->paid_amount->toBe(70.0) // 30 + 40
        ->refunded_amount->toBe(10.0);
});

// STATUS CALCULATIONS /////////////////////////////////////////////////////////////////////////////

it('fills status correctly based on financial state', function (InvoiceStatus $status, float $paidAmount, float $refundedAmount) {
    // Arrange
    $invoice = Invoice::factory()->pending()->create([
        'total' => 100.0,
        'paid_amount' => $paidAmount,
        'refunded_amount' => $refundedAmount,
    ]);

    // Assert
    expect($invoice->fillStatus())
        ->status->toBe($status);
})->with([
    'no payments' => [InvoiceStatus::Draft, 0.0, 0.0],
    'partial payment' => [InvoiceStatus::Sent, 50.0, 0.0],
    'full payment' => [InvoiceStatus::Paid, 100.0, 0.0],
    'overpayment' => [InvoiceStatus::Paid, 120.0, 0.0],
    'with refunds' => [InvoiceStatus::Refunded, 100.0, 30.0],
    'partial with refunds' => [InvoiceStatus::Refunded, 50.0, 20.0],
]);

it('reverts to draft when payments are removed from sent/paid invoice', function (InvoiceStatus $status) {
    // Arrange
    $invoice = Invoice::factory()->ofStatus($status)->create([
        'total' => 100.0,
        'paid_amount' => 0.0,
        'refunded_amount' => 0.0,
    ]);

    // Assert
    expect($invoice->fillStatus())
        ->status->toBe(InvoiceStatus::Draft);
})->with([
    'from sent' => [InvoiceStatus::Sent],
    'from paid' => [InvoiceStatus::Paid],
]);

it('preserves existing status when no financial changes occur', function () {
    // Arrange
    $invoice = Invoice::factory()->ofStatus(InvoiceStatus::Issued)->create([
        'total' => 100.0,
        'paid_amount' => 0.0,
        'refunded_amount' => 0.0,
    ]);

    // Assert
    expect($invoice->fillStatus())
        ->status->toBe(InvoiceStatus::Issued); // Should preserve existing status
});

// COMPUTED ATTRIBUTES /////////////////////////////////////////////////////////////////////////

it('calculates subtotal correctly', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'task_total' => 100.50,
        'order_total' => 75.25,
    ]);

    // Assert
    expect($invoice->subtotal)->toBe(175.75); // 100.50 + 75.25
});

it('calculates subtotal correctly with zero values', function () {
    // Arrange
    $invoice = Invoice::factory()->draft()->create();

    // Assert
    expect($invoice->subtotal)->toBe(0.0);
});

it('calculates net amount correctly', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'task_total' => 100.00,
        'order_total' => 50.00,
        'discount_amount' => 25.00,
    ]);

    // Assert
    expect($invoice->net_amount)->toBe(125.0); // 150 - 25
});

it('calculates net amount correctly with no discount', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'task_total' => 100.00,
        'order_total' => 50.00,
        'discount_amount' => 0,
    ]);

    // Assert
    expect($invoice->net_amount)->toBe(150.0); // subtotal when no discount
});

it('calculates balance correctly', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'task_total' => 100.00,
        'order_total' => 50.00,    // subtotal = 150
        'discount_amount' => 25.00, // net_amount = 125
        'total' => 125.00,         // total set explicitly
        'paid_amount' => 80.00,
        'refunded_amount' => 10.00,
    ]);

    // Assert
    expect($invoice->balance)->toBe(55.0); // 125 - 80 + 10
});

it('calculates balance correctly when fully paid', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'task_total' => 100.00,
        'order_total' => 50.00,    // subtotal = 150
        'discount_amount' => 25.00, // net_amount = 125
        'total' => 125.00,         // total set explicitly
        'paid_amount' => 125.00,
        'refunded_amount' => 0,
    ]);

    // Assert
    expect($invoice->balance)->toBe(0.0); // 125 - 125 + 0
});

it('calculates balance correctly with overpayment', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'task_total' => 100.00,
        'order_total' => 50.00,    // subtotal = 150
        'discount_amount' => 25.00, // net_amount = 125
        'total' => 125.00,         // total set explicitly
        'paid_amount' => 140.00,
        'refunded_amount' => 0,
    ]);

    // Assert
    expect($invoice->balance)->toBe(-15.0); // 125 - 140 + 0 (negative means overpaid)
});

it('syncs total from net amount automatically', function () {
    // Arrange
    $invoice = Invoice::factory()->draft()->create([
        'task_total' => 100.00,
        'order_total' => 50.00,    // subtotal = 150
        'discount_amount' => 25.00, // net_amount = 125
        // total starts at 0 from draft state
    ]);

    // Act
    $invoice->syncTotal()->save();

    // Assert
    expect($invoice->total)->toBe(125.0); // should match net_amount
    expect($invoice->subtotal)->toBe(150.0);
    expect($invoice->net_amount)->toBe(125.0);
});

it('allows manual total override after sync', function () {
    // Arrange
    $invoice = Invoice::factory()->draft()->create([
        'task_total' => 100.00,
        'order_total' => 50.00,    // subtotal = 150
        'discount_amount' => 25.00, // net_amount = 125
        // total starts at 0 from draft state
    ]);

    // Act - sync total first
    $invoice->syncTotal()->save();
    expect($invoice->total)->toBe(125.0);

    // Then manually override total (business adjustment)
    $invoice->total = 120.00;
    $invoice->save();

    // Assert
    expect($invoice->total)->toBe(120.0); // manually set total
    expect($invoice->net_amount)->toBe(125.0); // computed amount unchanged
    expect($invoice->balance)->toBe(120.0); // balance uses manual total
});
