<?php

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a transaction with valid attributes', function () {
    // Arrange & Act
    $transaction = Transaction::factory()->create();

    // Assert
    expect($transaction->invoice_id)->not->toBeNull();
    expect($transaction->amount)->toBeGreaterThan(0);
    expect($transaction->method)->toBeInstanceOf(TransactionMethod::class);
    expect($transaction->type)->toBeInstanceOf(TransactionType::class);
});

it('can create a transaction for an invoice', function () {
    // Arrange
    $invoice = Invoice::factory()->create();

    // Act
    $transaction = Transaction::factory()->forInvoice($invoice)->create();

    // Assert
    expect($transaction->invoice->id)->toBe($invoice->id);
});

it('can create transaction without note', function () {
    // Arrange & Act
    $transaction = Transaction::factory()->withoutNote()->create();

    // Assert
    expect($transaction->note)->toBeNull();
});

it('can create a transaction of method', function (TransactionMethod $method) {
    // Arrange & Act
    $transaction = Transaction::factory()->ofMethod($method)->create();

    // Assert
    expect($transaction->method)->toBe($method);
})->with(TransactionMethod::cases());

it('can create a transaction of type', function (TransactionType $type) {
    // Arrange & Act
    $transaction = Transaction::factory()->ofType($type)->create();

    // Assert
    expect($transaction->type)->toBe($type);
})->with(TransactionType::cases());

it('can update a transaction', function () {
    // Arrange
    $transaction = Transaction::factory()->create();

    // Act
    $transaction->update([
        'amount' => 200.0,
        'note' => 'Updated note',
        'method' => TransactionMethod::Card,
        'type' => TransactionType::Refund,
    ]);

    // Assert
    expect($transaction->amount)->toBe(200.0);
    expect($transaction->note)->toBe('Updated note');
    expect($transaction->method)->toBe(TransactionMethod::Card);
    expect($transaction->type)->toBe(TransactionType::Refund);
});

it('can delete a transaction', function () {
    // Arrange
    $transaction = Transaction::factory()->create();

    // Act
    $transaction->delete();

    // Assert
    expect(Transaction::find($transaction->id))->toBeNull();
});

it('belongs to an invoice', function () {
    // Arrange & Act
    $invoice = Invoice::factory()->create();
    $transaction = Transaction::factory()->forInvoice($invoice)->create();

    // Assert
    expect($transaction->invoice_id)->toBe($invoice->id);
    expect($transaction->invoice->id)->toBe($invoice->id);
});

it('can filter transactions by method scope', function (TransactionMethod $method) {
    // Arrange
    Transaction::factory()->ofMethod($method)->create();

    // Act
    $transactions = Transaction::ofMethod($method)->get();

    // Assert
    expect($transactions)->toHaveCount(1);
    expect($transactions->first()->method)->toBe($method);
})->with(TransactionMethod::cases());

it('can filter transactions by type scope', function (TransactionType $type) {
    // Arrange
    Transaction::factory()->ofType($type)->create();

    // Act
    $transactions = Transaction::ofType($type)->get();

    // Assert
    expect($transactions)->toHaveCount(1);
    expect($transactions->first()->type)->toBe($type);
})->with(TransactionType::cases());
