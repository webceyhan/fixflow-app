<?php

use App\Enums\AdjustmentType;
use App\Enums\InvoiceStatus;
use App\Models\Adjustment;
use App\Models\Concerns\HasDueDate;
use App\Models\Concerns\HasProgress;
use App\Models\Concerns\HasStatus;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Observers\InvoiceObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Invoice::class,
    concerns: [
        HasDueDate::class,
        HasProgress::class,
        HasStatus::class,
    ],
    observers: [
        InvoiceObserver::class,
    ],
    defaults: [
        'status' => InvoiceStatus::Draft,
    ],
    fillables: [
        'total',
        'task_total',
        'order_total',
        'discount_amount',
        'fee_amount',
        'compensation_amount',
        'bonus_amount',
        'paid_amount',
        'refunded_amount',
        'due_date',
        'status',
    ],
    casts: [
        'total' => 'float',
        'task_total' => 'float',
        'order_total' => 'float',
        'discount_amount' => 'float',
        'fee_amount' => 'float',
        'compensation_amount' => 'float',
        'bonus_amount' => 'float',
        'paid_amount' => 'float',
        'refunded_amount' => 'float',
    ],
    relations: [
        'ticket' => BelongsTo::class,
        'transactions' => HasMany::class,
        'adjustments' => HasMany::class,
    ]
);

// ACCESSOR TESTS //////////////////////////////////////////////////////////////////////////////////

it('has calculated subtotal', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'task_total' => 100.0,
        'order_total' => 50.0,
    ]);

    // Assert
    expect($invoice->subtotal)->toBe(150.0);
});

it('has calculated balance', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'total' => 100.0,
        'paid_amount' => 80.0,
        'refunded_amount' => 10.0,
    ]);

    // Assert
    expect($invoice->balance)->toBe(30.0); // 100 - 80 + 10
});

it('has calculated net amount', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'task_total' => 100.0,
        'order_total' => 50.0,
        'fee_amount' => 10.0,
        'discount_amount' => 15.0,
        'compensation_amount' => 5.0,
        'bonus_amount' => 3.0,
    ]);

    // Assert - subtotal(150) + fee(10) - discount(15) - compensation(5) - bonus(3) = 137
    expect($invoice->net_amount)->toBe(137.0);
});

// RELATION TESTS //////////////////////////////////////////////////////////////////////////////////

it('belongs to ticket relationship', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    // Assert
    expect($invoice->ticket)->toBeInstanceOf(Ticket::class);
    expect($invoice->ticket->id)->toBe($ticket->id);
});

it('has many transactions relationship', function () {
    // Arrange
    $invoice = Invoice::factory()->create();
    Transaction::factory()->count(2)->forInvoice($invoice)->create();

    // Assert
    expect($invoice->transactions)->toHaveCount(2);
    expect($invoice->transactions->first())->toBeInstanceOf(Transaction::class);
    expect($invoice->transactions->first()->invoice_id)->toBe($invoice->id);
});

// METHOD TESTS ////////////////////////////////////////////////////////////////////////////////////

it('can fill task total', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    // Create billable and non-billable tasks
    Task::factory()->forTicket($ticket)->billable(100.0)->create();
    Task::factory()->forTicket($ticket)->billable(50.0)->create();
    Task::factory()->forTicket($ticket)->notBillable()->create();

    // Act & Assert
    expect($invoice->fillTaskTotal()->task_total)->toBe(150.0);
});

it('can fill order total', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    // Create billable and non-billable orders
    Order::factory()->forTicket($ticket)->billable(200.0)->create();
    Order::factory()->forTicket($ticket)->billable(100.0)->create();
    Order::factory()->forTicket($ticket)->notBillable()->create();

    // Act & Assert
    expect($invoice->fillOrderTotal()->order_total)->toBe(300.0);
});

it('can fill adjustment amounts', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'task_total' => 100.0,
        'order_total' => 50.0,
    ]);

    // Create different adjustment types with fixed amounts
    Adjustment::factory()->forInvoice($invoice)->ofType(AdjustmentType::Discount)->withPercentage(10.0)->create();
    Adjustment::factory()->forInvoice($invoice)->ofType(AdjustmentType::Fee)->withAmount(20.0)->create();
    Adjustment::factory()->forInvoice($invoice)->ofType(AdjustmentType::Compensation)->withAmount(10.0)->create();
    Adjustment::factory()->forInvoice($invoice)->ofType(AdjustmentType::Bonus)->withPercentage(5.0)->create();

    // Act & Assert
    expect($invoice->fillAdjustmentAmounts())
        ->discount_amount->toBe(15.0) // 10% of 150.0
        ->fee_amount->toBe(20.0)
        ->compensation_amount->toBe(10.0)
        ->bonus_amount->toBe(7.5); // 5% of 150.0
});

it('can determine if invoice has percentage based adjustments', function () {
    // Arrange
    $invoice = Invoice::factory()->create();

    // Assert
    expect($invoice->hasPercentageAdjustments())->toBeFalse();

    // Create percentage-based adjustment
    Adjustment::factory()->forInvoice($invoice)->withPercentage(10.0)->create();

    // Assert
    expect($invoice->refresh()->hasPercentageAdjustments())->toBeTrue();
});

it('can fill transaction amounts', function () {
    // Arrange
    $invoice = Invoice::factory()->create();
    Transaction::factory()->count(2)->forInvoice($invoice)->payment(100.0)->create();

    // Act & Assert
    expect($invoice->fillTransactionAmounts())
        ->paid_amount->toBe(200.0)
        ->refunded_amount->toBe(0.0);

    // Create a refund transaction
    Transaction::factory()->count(2)->forInvoice($invoice)->refund(25.0)->create();

    // Act & Assert
    expect($invoice->fillTransactionAmounts())
        ->paid_amount->toBe(200.0)
        ->refunded_amount->toBe(50.0);
});

it('can sync total', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'task_total' => 100.0,
        'order_total' => 50.0,
        'discount_amount' => 20.0,
        'fee_amount' => 15.0,
        'compensation_amount' => 10.0,
        'bonus_amount' => 5.0,
    ]);

    // Act & Assert
    // Calculation: subtotal(150) + fee(15) - discount(20) - compensation(10) - bonus(5) = 130.0
    expect($invoice->syncTotal()->total)->toBe(130.0);
});

it('can fill status', function (InvoiceStatus $status, float $paidAmount, float $refundedAmount) {
    // Arrange
    $invoice = Invoice::factory()->pending()->create([
        'total' => 100.0,
        'paid_amount' => $paidAmount,
        'refunded_amount' => $refundedAmount,
    ]);

    // Assert
    expect($invoice->fillStatus()->status)->toBe($status);
})->with([
    'no payments' => [InvoiceStatus::Draft, 0.0, 0.0],
    'partial payment' => [InvoiceStatus::Sent, 50.0, 0.0],
    'full payment' => [InvoiceStatus::Paid, 100.0, 0.0],
    'overpayment' => [InvoiceStatus::Paid, 120.0, 0.0],
    'with refunds' => [InvoiceStatus::Refunded, 100.0, 30.0],
    'partial with refunds' => [InvoiceStatus::Refunded, 50.0, 20.0],
]);
