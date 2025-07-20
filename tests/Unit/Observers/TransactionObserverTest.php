<?php

use App\Models\Invoice;
use App\Models\Transaction;
use App\Observers\TransactionObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->observer = new TransactionObserver;
});

it('updates invoice transaction amounts on creation', function () {
    // Arrange
    $invoice = mock(Invoice::class);
    $transaction = mock(Transaction::class);

    $transaction->shouldReceive('load')
        ->once()
        ->with('invoice')
        ->andReturnSelf();

    $transaction->shouldReceive('getAttribute')
        ->with('invoice')
        ->once()
        ->andReturn($invoice);

    $invoice->shouldReceive('fillTransactionAmounts')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('fillStatus')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('save')
        ->once()
        ->andReturn(true);

    // Act
    $this->observer->created($transaction);
});

it('does nothing when amount or type was not changed', function () {
    // Arrange
    $transaction = mock(Transaction::class);

    $transaction->shouldReceive('wasChanged')
        ->once()
        ->with(['amount', 'type'])
        ->andReturn(false);

    // transaction should not be loaded or modified when no relevant changes
    $transaction->shouldNotReceive('load');

    // Act
    $this->observer->updated($transaction);
});

it('updates invoice transaction amounts when amount or type was changed', function () {
    // Arrange
    $invoice = mock(Invoice::class);
    $transaction = mock(Transaction::class);

    $transaction->shouldReceive('wasChanged')
        ->once()
        ->with(['amount', 'type'])
        ->andReturn(true);

    $transaction->shouldReceive('load')
        ->once()
        ->with('invoice')
        ->andReturnSelf();

    $transaction->shouldReceive('getAttribute')
        ->with('invoice')
        ->once()
        ->andReturn($invoice);

    $invoice->shouldReceive('fillTransactionAmounts')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('fillStatus')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('save')
        ->once()
        ->andReturn(true);

    $this->observer->updated($transaction);
});

it('updates invoice transaction amounts on deletion', function () {
    // Arrange
    $invoice = mock(Invoice::class);
    $transaction = mock(Transaction::class);

    $transaction->shouldReceive('load')
        ->once()
        ->with('invoice')
        ->andReturnSelf();

    $transaction->shouldReceive('getAttribute')
        ->with('invoice')
        ->once()
        ->andReturn($invoice);

    $invoice->shouldReceive('fillTransactionAmounts')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('fillStatus')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('save')
        ->once()
        ->andReturn(true);

    // Act
    $this->observer->deleted($transaction);
});
