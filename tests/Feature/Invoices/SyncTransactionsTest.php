<?php

use App\Actions\Invoices\SyncInvoiceTransactions;
use App\Models\Invoice;
use App\Models\Transaction;

it('syncs paid_amount when payment transactions are added', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['paid_amount' => 0]);

    // Act - Create payment transactions
    Transaction::factory()->forInvoice($invoice)->payment()->create(['amount' => 100]);
    Transaction::factory()->forInvoice($invoice)->payment()->create(['amount' => 50]);

    app(SyncInvoiceTransactions::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->paid_amount)->toBe(150.0);
});

it('syncs paid_amount when payment transactions are updated', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['paid_amount' => 100]);
    $transaction = Transaction::factory()->forInvoice($invoice)->payment()->create(['amount' => 100]);

    // Act - Update transaction amount
    $transaction->update(['amount' => 200]);
    app(SyncInvoiceTransactions::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->paid_amount)->toBe(200.0);
});

it('syncs paid_amount when payment transactions are deleted', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['paid_amount' => 150]);
    $transaction1 = Transaction::factory()->forInvoice($invoice)->payment()->create(['amount' => 100]);
    $transaction2 = Transaction::factory()->forInvoice($invoice)->payment()->create(['amount' => 50]);

    // Act - Delete one transaction
    $transaction1->delete();
    app(SyncInvoiceTransactions::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->paid_amount)->toBe(50.0);
});

it('syncs refunded_amount when refund transactions are added', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['refunded_amount' => 0]);

    // Act - Create refund transactions
    Transaction::factory()->forInvoice($invoice)->refund()->create(['amount' => 30]);
    Transaction::factory()->forInvoice($invoice)->refund()->create(['amount' => 20]);

    app(SyncInvoiceTransactions::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->refunded_amount)->toBe(50.0);
});

it('includes deposit transactions in paid_amount', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['paid_amount' => 0]);

    // Act - Create mix of payment and deposit transactions
    Transaction::factory()->forInvoice($invoice)->payment()->create(['amount' => 100]);
    Transaction::factory()->forInvoice($invoice)->deposit()->create(['amount' => 50]);

    app(SyncInvoiceTransactions::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->paid_amount)->toBe(150.0);
});

it('correctly handles mix of payment, deposit, and refund transactions', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'paid_amount' => 0,
        'refunded_amount' => 0,
    ]);

    // Act - Create mix of transaction types
    Transaction::factory()->forInvoice($invoice)->payment()->create(['amount' => 100]);
    Transaction::factory()->forInvoice($invoice)->deposit()->create(['amount' => 50]);
    Transaction::factory()->forInvoice($invoice)->refund()->create(['amount' => 30]);

    app(SyncInvoiceTransactions::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->paid_amount)->toBe(150.0);
    expect($invoice->fresh()->refunded_amount)->toBe(30.0);
});

it('calculates paid_amount as zero when no payment or deposit transactions exist', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['paid_amount' => 500]);

    // Create only refund transactions
    Transaction::factory()->forInvoice($invoice)->refund()->create(['amount' => 100]);

    // Act
    app(SyncInvoiceTransactions::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->paid_amount)->toBe(0.0);
});

it('calculates refunded_amount as zero when no refund transactions exist', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['refunded_amount' => 500]);

    // Create only payment transactions
    Transaction::factory()->forInvoice($invoice)->payment()->create(['amount' => 100]);

    // Act
    app(SyncInvoiceTransactions::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->refunded_amount)->toBe(0.0);
});
