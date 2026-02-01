<?php

use App\Enums\InvoiceStatus;
use App\Models\Adjustment;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasDueDate;
use App\Models\Concerns\HasStatus;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Invoice::class,
    concerns: [
        HasActiveStatus::class,
        HasDueDate::class,
        HasStatus::class,
    ],
    defaults: [
        'total' => 0,
        'task_total' => 0,
        'order_total' => 0,
        'discount_amount' => 0,
        'fee_amount' => 0,
        'paid_amount' => 0,
        'refunded_amount' => 0,
        'status' => InvoiceStatus::Draft,
    ],
    fillables: [
        'ticket_id',
        'total',
        'task_total',
        'order_total',
        'discount_amount',
        'fee_amount',
        'paid_amount',
        'refunded_amount',
        'due_date',
        'note',
        'status',
    ],
    hiddens: [
        // No hidden attributes
    ],
    casts: [
        'total' => 'float',
        'task_total' => 'float',
        'order_total' => 'float',
        'discount_amount' => 'float',
        'fee_amount' => 'float',
        'paid_amount' => 'float',
        'refunded_amount' => 'float',
        'status' => InvoiceStatus::class,
        'due_date' => 'date',
    ],
    relations: [
        'ticket' => BelongsTo::class,
        'transactions' => HasMany::class,
        'adjustments' => HasMany::class,
    ]
);

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
    Transaction::factory()->count(3)->forInvoice($invoice)->create();

    // Assert
    expect($invoice->transactions)->toHaveCount(3);
    expect($invoice->transactions->first())->toBeInstanceOf(Transaction::class);
});

it('has many adjustments relationship', function () {
    // Arrange
    $invoice = Invoice::factory()->create();
    Adjustment::factory()->count(2)->forInvoice($invoice)->create();

    // Assert
    expect($invoice->adjustments)->toHaveCount(2);
    expect($invoice->adjustments->first())->toBeInstanceOf(Adjustment::class);
});

// SCOPE TESTS /////////////////////////////////////////////////////////////////////////////////////

// METHOD TESTS ////////////////////////////////////////////////////////////////////////////////////
