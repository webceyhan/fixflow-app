<?php

use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the transaction show page', function () {
    // Arrange
    $viewer = User::factory()->create();
    $invoice = Invoice::factory()->create();
    $transaction = Transaction::factory()->forInvoice($invoice)->create();

    // Act
    $response = actingAs($viewer)->get(route('transactions.show', $transaction));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('transactions/Edit')
        ->has('transaction')
        ->where('transaction.id', $transaction->id)
        ->where('transaction.amount', $transaction->amount)
    );
});

it('loads transaction with invoice relationship', function () {
    // Arrange
    $viewer = User::factory()->create();
    $invoice = Invoice::factory()->create();
    $transaction = Transaction::factory()->forInvoice($invoice)->create();

    // Act
    $response = actingAs($viewer)->get(route('transactions.show', $transaction));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('transaction.invoice')
        ->where('transaction.invoice.id', $invoice->id)
    );
});

it('loads transaction with nested ticket relationship', function () {
    // Arrange
    $viewer = User::factory()->create();
    $invoice = Invoice::factory()->create();
    $transaction = Transaction::factory()->forInvoice($invoice)->create();

    // Act
    $response = actingAs($viewer)->get(route('transactions.show', $transaction));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('transaction.invoice.ticket')
        ->where('transaction.invoice.ticket.id', $invoice->ticket->id)
    );
});

it('loads transaction with nested device relationship', function () {
    // Arrange
    $viewer = User::factory()->create();
    $invoice = Invoice::factory()->create();
    $transaction = Transaction::factory()->forInvoice($invoice)->create();

    // Act
    $response = actingAs($viewer)->get(route('transactions.show', $transaction));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('transaction.invoice.ticket.device')
        ->where('transaction.invoice.ticket.device.id', $invoice->ticket->device->id)
    );
});

it('loads transaction with nested customer relationship', function () {
    // Arrange
    $viewer = User::factory()->create();
    $invoice = Invoice::factory()->create();
    $transaction = Transaction::factory()->forInvoice($invoice)->create();

    // Act
    $response = actingAs($viewer)->get(route('transactions.show', $transaction));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('transaction.invoice.ticket.customer')
        ->where('transaction.invoice.ticket.customer.id', $invoice->ticket->customer->id)
    );
});
