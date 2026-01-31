<?php

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the edit invoice form', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    // Act
    $response = actingAs($manager)->get(route('invoices.edit', $invoice));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('invoices/Edit')
        ->has('invoice')
        ->where('invoice.id', $invoice->id)
    );
});

it('validates required fields on update', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();

    // Act
    $response = actingAs($manager)->put(route('invoices.update', $invoice), [
        'total' => null,
    ]);

    // Assert
    $response->assertSessionHasErrors(['total', 'discount_amount', 'fee_amount', 'status']);
});

it('validates amount fields are numeric and non-negative', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();

    // Act
    $response = actingAs($manager)->put(route('invoices.update', $invoice), [
        'total' => -100,
        'discount_amount' => 'invalid',
        'fee_amount' => 0,
        'due_date' => now()->toDateString(),
        'status' => InvoiceStatus::Draft->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['total', 'discount_amount']);
});

it('does not allow changing ticket_id', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();
    $originalTicketId = $invoice->ticket_id;

    // Act
    $response = actingAs($manager)->put(route('invoices.update', $invoice), [
        'total' => 500,
        'discount_amount' => 0,
        'fee_amount' => 0,
        'due_date' => now()->addDays(30)->toDateString(),
        'status' => InvoiceStatus::Issued->value,
    ]);

    // Assert
    $response->assertRedirect();
    expect($invoice->fresh()->ticket_id)->toBe($originalTicketId);
});

it('does not allow changing task_total or order_total', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create([
        'task_total' => 100,
        'order_total' => 50,
    ]);

    // Act
    $response = actingAs($manager)->put(route('invoices.update', $invoice), [
        'total' => 500,
        'discount_amount' => 0,
        'fee_amount' => 0,
        'due_date' => now()->addDays(30)->toDateString(),
        'status' => InvoiceStatus::Issued->value,
        'task_total' => 999, // Attempt to change
        'order_total' => 888, // Attempt to change
    ]);

    // Assert
    $response->assertRedirect();
    expect($invoice->fresh()->task_total)->toBe(100.0);
    expect($invoice->fresh()->order_total)->toBe(50.0);
});

it('updates an invoice successfully with all editable fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create([
        'total' => 100,
        'discount_amount' => 0,
        'fee_amount' => 0,
        'status' => InvoiceStatus::Draft,
        'note' => 'Original note',
    ]);

    // Act
    $response = actingAs($manager)->put(route('invoices.update', $invoice), [
        'total' => 500.50,
        'discount_amount' => 25.00,
        'fee_amount' => 10.00,
        'due_date' => now()->addDays(15)->toDateString(),
        'status' => InvoiceStatus::Paid->value,
        'note' => 'Updated payment note',
    ]);

    // Assert
    $response->assertRedirect(route('invoices.show', $invoice));
    expect($invoice->fresh())
        ->total->toBe(500.50)
        ->discount_amount->toBe(25.00)
        ->fee_amount->toBe(10.00)
        ->status->toBe(InvoiceStatus::Paid)
        ->note->toBe('Updated payment note');
});

it('allows updating status to all valid statuses', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create(['status' => InvoiceStatus::Draft]);

    // Act & Assert
    foreach (InvoiceStatus::cases() as $status) {
        $response = actingAs($manager)->put(route('invoices.update', $invoice), [
            'total' => $invoice->total,
            'discount_amount' => $invoice->discount_amount,
            'fee_amount' => $invoice->fee_amount,
            'due_date' => $invoice->due_date,
            'status' => $status->value,
        ]);

        $response->assertRedirect();
        expect($invoice->fresh()->status)->toBe($status);
    }
});

it('allows nullable note field', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create(['note' => 'Some note']);

    // Act
    $response = actingAs($manager)->put(route('invoices.update', $invoice), [
        'total' => $invoice->total,
        'discount_amount' => $invoice->discount_amount,
        'fee_amount' => $invoice->fee_amount,
        'due_date' => $invoice->due_date,
        'status' => $invoice->status->value,
        'note' => null,
    ]);

    // Assert
    $response->assertRedirect();
    expect($invoice->fresh()->note)->toBeNull();
});
