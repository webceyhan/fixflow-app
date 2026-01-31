<?php

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the edit transaction form', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();
    $transaction = Transaction::factory()->forInvoice($invoice)->create();

    // Act
    $response = actingAs($manager)->get(route('transactions.edit', $transaction));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('transactions/Edit')
        ->has('transaction')
        ->where('transaction.id', $transaction->id)
    );
});

it('validates required fields on update', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $transaction = Transaction::factory()->create();

    // Act
    $response = actingAs($manager)->put(route('transactions.update', $transaction), [
        'amount' => null,
    ]);

    // Assert
    $response->assertSessionHasErrors(['amount', 'method', 'type']);
});

it('does not allow changing invoice_id', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $transaction = Transaction::factory()->create();
    $originalInvoiceId = $transaction->invoice_id;

    // Act
    $response = actingAs($manager)->put(route('transactions.update', $transaction), [
        'amount' => 200,
        'method' => TransactionMethod::Card->value,
        'type' => TransactionType::Payment->value,
    ]);

    // Assert
    $response->assertRedirect();
    expect($transaction->fresh()->invoice_id)->toBe($originalInvoiceId);
});

it('validates amount is numeric and minimum 0 on update', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $transaction = Transaction::factory()->create();

    // Act
    $response = actingAs($manager)->put(route('transactions.update', $transaction), [
        'amount' => -50,
        'method' => TransactionMethod::Cash->value,
        'type' => TransactionType::Payment->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['amount']);
});

it('validates method is valid enum on update', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $transaction = Transaction::factory()->create();

    // Act
    $response = actingAs($manager)->put(route('transactions.update', $transaction), [
        'amount' => 100,
        'method' => 'invalid_method',
        'type' => TransactionType::Payment->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['method']);
});

it('validates type is valid enum on update', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $transaction = Transaction::factory()->create();

    // Act
    $response = actingAs($manager)->put(route('transactions.update', $transaction), [
        'amount' => 100,
        'method' => TransactionMethod::Cash->value,
        'type' => 'invalid_type',
    ]);

    // Assert
    $response->assertSessionHasErrors(['type']);
});

it('updates a transaction successfully with all fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();
    $transaction = Transaction::factory()->forInvoice($invoice)->create([
        'amount' => 50,
        'method' => TransactionMethod::Cash,
        'type' => TransactionType::Deposit,
        'note' => 'Original note',
    ]);
    $originalInvoiceId = $transaction->invoice_id;

    // Act
    $response = actingAs($manager)->put(route('transactions.update', $transaction), [
        'amount' => 200.75,
        'method' => TransactionMethod::Card->value,
        'type' => TransactionType::Payment->value,
        'note' => 'Updated payment note',
    ]);

    // Assert
    $response->assertRedirect(route('invoices.show', $transaction->invoice_id));
    expect($transaction->fresh())
        ->invoice_id->toBe($originalInvoiceId)
        ->amount->toBe(200.75)
        ->method->toBe(TransactionMethod::Card)
        ->type->toBe(TransactionType::Payment)
        ->note->toBe('Updated payment note');
});

it('updates a transaction successfully with only required fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $transaction = Transaction::factory()->create([
        'amount' => 100,
        'method' => TransactionMethod::Cash,
        'type' => TransactionType::Payment,
        'note' => 'Some note',
    ]);

    // Act
    $response = actingAs($manager)->put(route('transactions.update', $transaction), [
        'amount' => 150,
        'method' => TransactionMethod::Online->value,
        'type' => TransactionType::Refund->value,
        'note' => null,
    ]);

    // Assert
    $response->assertRedirect();
    expect($transaction->fresh())
        ->amount->toBe(150.0)
        ->method->toBe(TransactionMethod::Online)
        ->type->toBe(TransactionType::Refund)
        ->note->toBeNull();
});

it('syncs invoice amounts after updating transaction', function () {
    // Arrange
    $invoice = Invoice::factory()->create();
    $transaction = Transaction::factory()->forInvoice($invoice)->payment()->create(['amount' => 100]);
    $manager = User::factory()->manager()->create();

    // Sync first to set initial state
    $invoice->forceFill(['paid_amount' => 100])->save();

    // Act
    actingAs($manager)->put(route('transactions.update', $transaction), [
        'amount' => 200,
        'method' => TransactionMethod::Cash->value,
        'type' => TransactionType::Payment->value,
    ]);

    // Assert
    expect($invoice->fresh()->paid_amount)->toBe(200.0);
});

it('redirects to invoice show page after update', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $transaction = Transaction::factory()->create();

    // Act
    $response = actingAs($manager)->put(route('transactions.update', $transaction), [
        'amount' => 100,
        'method' => TransactionMethod::Cash->value,
        'type' => TransactionType::Payment->value,
    ]);

    // Assert
    $response->assertRedirect(route('invoices.show', $transaction->invoice_id));
});
