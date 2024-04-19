<?php

use App\Models\Invoice;

it('can determine if invoice is overdue', function () {
    $invoice = Invoice::factory()->overdue()->create();

    expect($invoice->isOverdue())->toBeTrue();
    expect($invoice->due_date->isPast())->toBeTrue();
});

it('can determine if invoice is not overdue', function () {
    $invoice = Invoice::factory()->create();

    expect($invoice->isOverdue())->toBeFalse();
    expect($invoice->due_date->isPast())->toBeFalse();
});

it('can filter invoices by overdue scope', function () {
    Invoice::factory()->create();
    Invoice::factory()->overdue()->create();

    expect(Invoice::overdue()->count())->toBe(1);
    expect(Invoice::overdue()->first()->isOverdue())->toBeTrue();
});
