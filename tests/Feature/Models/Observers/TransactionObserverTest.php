<?php

use App\Models\Invoice;
use App\Models\Transaction;

it('can update invoice total_paid on all events as payment', function () {
    $invoice = Invoice::factory()->create();
    $transaction = Transaction::factory()->for($invoice)->create();

    $invoice->refresh();

    expect($invoice->total_paid)->toBe($transaction->amount);

    // update transaction amount
    $transaction->update(['amount' => 100.0]);
    $invoice->refresh();

    expect($invoice->total_paid)->toBe(100.0);

    // delete transaction
    $transaction->delete();
    $invoice->refresh();

    expect($invoice->total_paid)->toBe(0.0);
});

it('can update invoice total_paid on all events as refund', function () {
    $invoice = Invoice::factory()->create();
    $transaction = Transaction::factory()->for($invoice)->refund()->create();

    $invoice->refresh();

    expect($invoice->total_refunded)->toBe($transaction->amount);

    // update transaction amount
    $transaction->update(['amount' => 100.0]);
    $invoice->refresh();

    expect($invoice->total_refunded)->toBe(100.0);

    // delete transaction
    $transaction->delete();
    $invoice->refresh();

    expect($invoice->total_refunded)->toBe(0.0);
});
