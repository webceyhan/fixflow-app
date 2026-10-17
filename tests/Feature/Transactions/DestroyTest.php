<?php

use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('deletes a transaction successfully', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $transaction = Transaction::factory()->create();

    // Act
    $response = actingAs($manager)->delete(route('transactions.destroy', $transaction));

    // Assert
    $response->assertRedirect(route('invoices.show', $transaction->invoice_id));
    expect(Transaction::find($transaction->id))->toBeNull();
});

it('syncs invoice amounts after deleting transaction', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();
    $transaction = Transaction::factory()->forInvoice($invoice)->payment()->create(['amount' => 100]);

    // Sync first to set initial state
    $invoice->forceFill(['paid_amount' => 100])->save();

    // Act
    actingAs($manager)->delete(route('transactions.destroy', $transaction));

    // Assert
    expect($invoice->fresh()->paid_amount)->toBe(0.0);
});
