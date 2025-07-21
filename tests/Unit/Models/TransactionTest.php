<?php

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Models\Concerns\HasType;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Observers\TransactionObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Transaction::class,
    concerns: [
        HasType::class,
    ],
    observers: [
        TransactionObserver::class,
    ],
    defaults: [
        'method' => TransactionMethod::Cash,
        'type' => TransactionType::Payment,
    ],
    fillables: [
        'amount',
        'note',
        'method',
        'type',
    ],
    casts: [
        'amount' => 'float',
        'method' => TransactionMethod::class,
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

it('can filter by method scope', function (TransactionMethod $method) {
    // Arrange - Create transactions with different methods
    $transaction1 = Transaction::factory()->ofMethod($method)->create();
    $transaction2 = Transaction::factory()->ofMethod($method->next())->create();

    // Act
    $transactions = Transaction::ofMethod($method)->get();

    // Assert
    expect($transactions)->toHaveCount(1);
    expect($transactions->first()->method)->toBe($method);
    expect($transactions->first()->id)->toBe($transaction1->id);
})->with(TransactionMethod::cases());
