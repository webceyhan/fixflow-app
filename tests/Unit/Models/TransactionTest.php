<?php

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Models\Concerns\HasType;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Transaction::class,
    concerns: [
        HasType::class,
    ],
    defaults: [
        'amount' => 0,
        'method' => TransactionMethod::Cash,
        'type' => TransactionType::Payment,
    ],
    fillables: [
        'invoice_id',
        'amount',
        'note',
        'method',
        'type',
    ],
    hiddens: [
        // No hidden attributes
    ],
    casts: [
        'amount' => 'float',
        'method' => TransactionMethod::class,
        'type' => TransactionType::class,
    ],
    relations: [
        'invoice' => BelongsTo::class,
    ]
);

// RELATION TESTS //////////////////////////////////////////////////////////////////////////////////

it('belongs to invoice relationship', function () {
    // Arrange
    $invoice = Invoice::factory()->create();
    $transaction = Transaction::factory()->forInvoice($invoice)->create();

    // Assert
    expect($transaction->invoice)->toBeInstanceOf(Invoice::class);
    expect($transaction->invoice->id)->toBe($invoice->id);
});

// SCOPE TESTS /////////////////////////////////////////////////////////////////////////////////////

it('filters by method scope', function () {
    // Arrange
    Transaction::factory()->count(3)->create(['method' => 'cash']);
    Transaction::factory()->count(2)->create(['method' => 'card']);

    // Act
    $cashTransactions = Transaction::ofMethod(TransactionMethod::Cash)->get();

    // Assert
    expect($cashTransactions)->toHaveCount(3);
    expect($cashTransactions->every(fn ($t) => $t->method === TransactionMethod::Cash))->toBeTrue();
});

it('filters by type scope from HasType concern', function () {
    // Arrange
    Transaction::factory()->count(3)->create(['type' => 'payment']);
    Transaction::factory()->count(2)->create(['type' => 'refund']);

    // Act
    $paymentTransactions = Transaction::ofType(TransactionType::Payment)->get();

    // Assert
    expect($paymentTransactions)->toHaveCount(3);
    expect($paymentTransactions->every(fn ($t) => $t->type === TransactionType::Payment))->toBeTrue();
});

// METHOD TESTS ////////////////////////////////////////////////////////////////////////////////////
