<?php

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Invoice;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates an invoice with valid attributes', function () {
    // Arrange & Act
    $invoice = Invoice::factory()->create();

    // Assert
    expect($invoice->ticket_id)->not->toBeNull();
    expect($invoice->total)->toBeGreaterThanOrEqual(0);
    expect($invoice->subtotal)->toBeGreaterThanOrEqual(0);
    expect($invoice->discount_amount)->toBeGreaterThanOrEqual(0);
    expect($invoice->paid_amount)->toBeGreaterThanOrEqual(0);
    expect($invoice->refunded_amount)->toBeGreaterThanOrEqual(0);
    expect($invoice->due_date)->not->toBeNull();
    expect($invoice->status)->toBeInstanceOf(InvoiceStatus::class);
});

it('can create an invoice for a ticket', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    // Assert
    expect($invoice->ticket->id)->toBe($ticket->id);
});

it('can create a paid invoice', function () {
    // Arrange
    $invoice = Invoice::factory()->paid()->create();

    // Assert
    expect($invoice->status)->toBe(InvoiceStatus::Paid);
    expect($invoice->paid_amount)->toBe($invoice->total);
});

it('can create a refunded invoice', function () {
    // Arrange
    $invoice = Invoice::factory()->refunded()->create();

    // Assert
    expect($invoice->status)->toBe(InvoiceStatus::Refunded);
    expect($invoice->refunded_amount)->toBe($invoice->total);
});

it('can create an overdue invoice', function () {
    // Arrange
    $invoice = Invoice::factory()->overdue()->create();

    // Assert
    expect($invoice->isOverdue())->toBeTrue();
    expect($invoice->status)->toBe(InvoiceStatus::Issued);
});

it('can create an invoice of status', function (InvoiceStatus $status) {
    // Arrange & Act
    $invoice = Invoice::factory()->ofStatus($status)->create();

    // Assert
    expect($invoice->status)->toBe($status);
})->with(InvoiceStatus::cases());

it('can update an invoice', function () {
    // Arrange
    $invoice = Invoice::factory()->create();

    // Act
    $invoice->update([
        'total' => 180,
        'subtotal' => 200,
        'discount_amount' => 20,
        'paid_amount' => 100,
        'refunded_amount' => 0,
        'status' => InvoiceStatus::Sent,
    ]);

    // Assert
    expect($invoice->total)->toBe(180);
    expect($invoice->subtotal)->toBe(200);
    expect($invoice->discount_amount)->toBe(20);
    expect($invoice->paid_amount)->toBe(100);
    expect($invoice->refunded_amount)->toBe(0);
    expect($invoice->status)->toBe(InvoiceStatus::Sent);
});

it('can delete an invoice', function () {
    // Arrange
    $invoice = Invoice::factory()->create();

    // Act
    $invoice->delete();

    // Assert
    expect(Invoice::find($invoice->id))->toBeNull();
});

it('belongs to a ticket', function () {
    $ticket = Ticket::factory()->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    expect($invoice->ticket_id)->toBe($ticket->id);
    expect($invoice->ticket->id)->toBe($ticket->id);
});

it('belongs to a device via ticket', function () {
    $device = Device::factory()->create();
    $ticket = Ticket::factory()->forDevice($device)->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    expect($invoice->ticket_id)->toBe($ticket->id);
    expect($ticket->device->id)->toBe($device->id);
});

it('belongs to a customer via device', function () {
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();
    $ticket = Ticket::factory()->forDevice($device)->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    expect($invoice->ticket_id)->toBe($ticket->id);
    expect($invoice->customer->id)->toBe($customer->id);
});

it('can filter invoices by status scope', function (InvoiceStatus $status) {
    // Arrange
    Invoice::factory(2)->ofStatus($status)->create();

    // Act
    $invoices = Invoice::query()->ofStatus($status)->get();

    // Assert
    expect($invoices)->toHaveCount(2);
    expect($invoices->first()->status)->toBe($status);
})->with(InvoiceStatus::cases());

it('can filter invoices by overdue scope', function () {
    // Arrange
    Invoice::factory()->create();
    Invoice::factory()->overdue()->create();

    // Act
    $overdueInvoices = Invoice::query()->overdue()->get();

    // Assert
    expect($overdueInvoices)->toHaveCount(1);
    expect($overdueInvoices->first()->isOverdue())->toBeTrue();
});

it('can determine if an invoice is overdue', function () {
    // Arrange
    $invoice = Invoice::factory()->overdue()->create();

    // Assert
    expect($invoice->isOverdue())->toBeTrue();
});
