<?php

use App\Models\Invoice;
use App\Models\Transaction;
use App\Observers\TransactionObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->observer = new TransactionObserver;

    // Helpers

    $this->mockTransaction = function (bool $syncInvoice = false) {
        $transaction = mock(Transaction::class);

        if ($syncInvoice) {
            $invoice = mock(Invoice::class);
            $invoice->shouldReceive('fillTransactionAmounts')->once()->andReturnSelf();
            $invoice->shouldReceive('fillStatus')->once()->andReturnSelf();
            $invoice->shouldReceive('save')->once()->andReturn(true);

            $transaction->shouldReceive('load')->once()->with('invoice')->andReturnSelf();
            $transaction->shouldReceive('getAttribute')->once()->with('invoice')->andReturn($invoice);
        }

        return $transaction;
    };

    $this->mockTransactionWithUpdates = function (bool $amountOrTypeChanged = false) {
        $transaction = $this->mockTransaction(syncInvoice: $amountOrTypeChanged);

        $transaction->shouldReceive('wasChanged')
            ->once()
            ->with(['amount', 'type'])
            ->andReturn($amountOrTypeChanged);

        return $transaction;
    };
});

it('updates invoice transaction amounts on creation', function () {
    // Arrange
    $transaction = $this->mockTransaction(syncInvoice: true);

    // Act
    $this->observer->created($transaction);
});

it('does nothing when amount or type was not changed', function () {
    // Arrange
    $transaction = $this->mockTransactionWithUpdates();

    // Act
    $this->observer->updated($transaction);
});

it('updates invoice transaction amounts when amount or type was changed', function () {
    // Arrange
    $transaction = $this->mockTransactionWithUpdates(amountOrTypeChanged: true);

    // Act
    $this->observer->updated($transaction);
});

it('updates invoice transaction amounts on deletion', function () {
    // Arrange
    $transaction = $this->mockTransaction(syncInvoice: true);

    // Act
    $this->observer->deleted($transaction);
});
