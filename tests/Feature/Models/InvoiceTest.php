<?php

use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

it('can initialize invoice', function () {
    $invoice = new Invoice();

    expect($invoice->id)->toBeNull();
    expect($invoice->ticket_id)->toBeNull();
    expect($invoice->total)->toBe(0.0);
    expect($invoice->is_paid)->toBeFalse();
    expect($invoice->due_date)->toBeNull();
    expect($invoice->created_at)->toBeNull();
    expect($invoice->updated_at)->toBeNull();
});

it('can create invoice', function () {
    $invoice = Invoice::factory()->create();

    expect($invoice->id)->toBeInt();
    expect($invoice->ticket_id)->toBeInt();
    expect($invoice->total)->toBeFloat();
    expect($invoice->is_paid)->toBeFalse();
    expect($invoice->due_date)->toBeInstanceOf(Carbon::class);
    expect($invoice->created_at)->toBeInstanceOf(Carbon::class);
    expect($invoice->updated_at)->toBeInstanceOf(Carbon::class);
});

it('can create invoice as paid', function () {
    $invoice = Invoice::factory()->paid()->create();

    expect($invoice->is_paid)->toBeTrue();
});

it('can create invoice as overdue', function () {
    $invoice = Invoice::factory()->overdue()->create();

    expect($invoice->due_date)->toBeInstanceOf(Carbon::class);
    expect($invoice->due_date->isPast())->toBeTrue();
});

it('can update invoice', function () {
    $invoice = Invoice::factory()->create();

    $invoice->update([
        'total' => 100,
        'is_paid' => true,
        'due_date' => '2024-01-01',
    ]);

    expect($invoice->total)->toBe(100.0);
    expect($invoice->is_paid)->toBeTrue();
    expect($invoice->due_date->format('Y-m-d'))->toBe('2024-01-01');
});

it('can delete invoice', function () {
    $invoice = Invoice::factory()->create();

    $invoice->delete();

    expect(Invoice::find($invoice->id))->toBeNull();
});

// Ticket //////////////////////////////////////////////////////////////////////////////////////////

it('belongs to a ticket', function () {
    $ticket = Ticket::factory()->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    expect($invoice->ticket)->toBeInstanceOf(Ticket::class);
    expect($invoice->ticket->id)->toBe($ticket->id);
});

// Transactions ////////////////////////////////////////////////////////////////////////////////////

it('can have many transactions', function () {
    $invoice = Invoice::factory()->hasTransactions(2)->create();

    expect($invoice->transactions)->toHaveCount(2);
});

it('can delete invoice with transactions', function () {
    $invoice = Invoice::factory()->hasTransactions(2)->create();

    $invoice->delete();

    expect(Invoice::find($invoice->id))->toBeNull();
    expect(Transaction::count())->toBe(0);
});

// Unpaid //////////////////////////////////////////////////////////////////////////////////////////

it('can filter invoices by unpaid scope', function () {
    Invoice::factory()->create();
    Invoice::factory()->paid()->create();

    expect(Invoice::unpaid()->count())->toBe(1);
    expect(Invoice::unpaid()->first()->is_paid)->toBeFalse();
});
