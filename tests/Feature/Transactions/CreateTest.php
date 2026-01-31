<?php

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the create transaction form', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();

    // Act
    $response = actingAs($manager)->get("/invoices/{$invoice->id}/transactions/create");

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->component('transactions/Create'));
});

it('validates required fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();

    // Act
    $response = actingAs($manager)->post("/invoices/{$invoice->id}/transactions", []);

    // Assert
    $response->assertSessionHasErrors(['amount', 'method', 'type']);
});

it('returns 404 when invoice does not exist', function () {
    // Arrange
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/invoices/9999/transactions', [
        'amount' => 100,
        'method' => TransactionMethod::Cash->value,
        'type' => TransactionType::Payment->value,
    ]);

    // Assert
    $response->assertNotFound();
});

it('validates amount is numeric and minimum 0', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();

    // Act
    $response = actingAs($manager)->post("/invoices/{$invoice->id}/transactions", [
        'amount' => -10,
        'method' => TransactionMethod::Cash->value,
        'type' => TransactionType::Payment->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['amount']);
});

it('validates method is valid enum', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();

    // Act
    $response = actingAs($manager)->post("/invoices/{$invoice->id}/transactions", [
        'amount' => 100,
        'method' => 'invalid_method',
        'type' => TransactionType::Payment->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['method']);
});

it('validates type is valid enum', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();

    // Act
    $response = actingAs($manager)->post("/invoices/{$invoice->id}/transactions", [
        'amount' => 100,
        'method' => TransactionMethod::Cash->value,
        'type' => 'invalid_type',
    ]);

    // Assert
    $response->assertSessionHasErrors(['type']);
});

it('creates a transaction successfully with all fields', function () {
    // Arrange
    $invoice = Invoice::factory()->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/invoices/{$invoice->id}/transactions", [
        'amount' => 150.50,
        'method' => TransactionMethod::Card->value,
        'type' => TransactionType::Payment->value,
        'note' => 'Payment via credit card',
    ]);

    // Assert
    $response->assertRedirect();
    $transaction = Transaction::latest()->first();
    expect($transaction)->not->toBeNull()
        ->and($transaction->invoice_id)->toBe($invoice->id)
        ->and($transaction->amount)->toBe(150.50)
        ->and($transaction->method)->toBe(TransactionMethod::Card)
        ->and($transaction->type)->toBe(TransactionType::Payment)
        ->and($transaction->note)->toBe('Payment via credit card');
});

it('creates a transaction successfully with only required fields', function () {
    // Arrange
    $invoice = Invoice::factory()->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/invoices/{$invoice->id}/transactions", [
        'amount' => 100,
        'method' => TransactionMethod::Cash->value,
        'type' => TransactionType::Deposit->value,
    ]);

    // Assert
    $response->assertRedirect();
    $transaction = Transaction::latest()->first();
    expect($transaction)->not->toBeNull()
        ->and($transaction->invoice_id)->toBe($invoice->id)
        ->and($transaction->amount)->toBe(100.0)
        ->and($transaction->method)->toBe(TransactionMethod::Cash)
        ->and($transaction->type)->toBe(TransactionType::Deposit)
        ->and($transaction->note)->toBeNull();
});

it('syncs invoice amounts after creating transaction', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['paid_amount' => 0]);
    $manager = User::factory()->manager()->create();

    // Act
    actingAs($manager)->post("/invoices/{$invoice->id}/transactions", [
        'amount' => 200,
        'method' => TransactionMethod::Cash->value,
        'type' => TransactionType::Payment->value,
    ]);

    // Assert
    expect($invoice->fresh()->paid_amount)->toBe(200.0);
});

it('redirects to invoice show page after creation', function () {
    // Arrange
    $invoice = Invoice::factory()->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/invoices/{$invoice->id}/transactions", [
        'amount' => 100,
        'method' => TransactionMethod::Cash->value,
        'type' => TransactionType::Payment->value,
    ]);

    // Assert
    $response->assertRedirect(route('invoices.show', $invoice));
});
