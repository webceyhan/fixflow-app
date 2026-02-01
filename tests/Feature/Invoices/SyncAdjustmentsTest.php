<?php

use App\Actions\Invoices\SyncInvoiceAdjustments;
use App\Models\Adjustment;
use App\Models\Invoice;

it('syncs discount_amount when discount adjustments are added', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['discount_amount' => 0]);

    // Act - Create fixed discount adjustments
    Adjustment::factory()->forInvoice($invoice)->discount()->fixed()->create(['amount' => 100, 'is_percent' => false]);
    Adjustment::factory()->forInvoice($invoice)->discount()->fixed()->create(['amount' => 50, 'is_percent' => false]);

    app(SyncInvoiceAdjustments::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->discount_amount)->toBe(150.0);
});

it('syncs discount_amount when discount adjustments are updated', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['discount_amount' => 100]);
    $adjustment = Adjustment::factory()->forInvoice($invoice)->discount()->fixed()->create(['amount' => 100, 'is_percent' => false]);

    // Act - Update adjustment amount
    $adjustment->update(['amount' => 200]);
    app(SyncInvoiceAdjustments::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->discount_amount)->toBe(200.0);
});

it('syncs discount_amount when discount adjustments are deleted', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['discount_amount' => 150]);
    $adjustment1 = Adjustment::factory()->forInvoice($invoice)->discount()->fixed()->create(['amount' => 100, 'is_percent' => false]);
    $adjustment2 = Adjustment::factory()->forInvoice($invoice)->discount()->fixed()->create(['amount' => 50, 'is_percent' => false]);

    // Act - Delete one adjustment
    $adjustment1->delete();
    app(SyncInvoiceAdjustments::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->discount_amount)->toBe(50.0);
});

it('syncs fee_amount when fee adjustments are added', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['fee_amount' => 0]);

    // Act - Create fixed fee adjustments
    Adjustment::factory()->forInvoice($invoice)->fee()->fixed()->create(['amount' => 30, 'is_percent' => false]);
    Adjustment::factory()->forInvoice($invoice)->fee()->fixed()->create(['amount' => 20, 'is_percent' => false]);

    app(SyncInvoiceAdjustments::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->fee_amount)->toBe(50.0);
});

it('calculates percentage-based discounts correctly', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'task_total' => 100,
        'order_total' => 50,
        'discount_amount' => 0,
    ]);

    // Act - Create 10% discount
    Adjustment::factory()->forInvoice($invoice)->discount()->percentage()->create(['amount' => 10, 'is_percent' => true]);

    app(SyncInvoiceAdjustments::class)->handle($invoice);

    // Assert - 10% of (100 + 50) = 15
    expect($invoice->fresh()->discount_amount)->toBe(15.0);
});

it('calculates percentage-based fees correctly', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'task_total' => 100,
        'order_total' => 50,
        'fee_amount' => 0,
    ]);

    // Act - Create 5% fee
    Adjustment::factory()->forInvoice($invoice)->fee()->percentage()->create(['amount' => 5, 'is_percent' => true]);

    app(SyncInvoiceAdjustments::class)->handle($invoice);

    // Assert - 5% of (100 + 50) = 7.5
    expect($invoice->fresh()->fee_amount)->toBe(7.5);
});

it('correctly handles mix of discount and fee adjustments', function () {
    // Arrange
    $invoice = Invoice::factory()->create([
        'task_total' => 200,
        'order_total' => 100,
        'discount_amount' => 0,
        'fee_amount' => 0,
    ]);

    // Act - Create mix of adjustments
    Adjustment::factory()->forInvoice($invoice)->discount()->fixed()->create(['amount' => 50, 'is_percent' => false]);
    Adjustment::factory()->forInvoice($invoice)->discount()->percentage()->create(['amount' => 10, 'is_percent' => true]); // 10% of 300 = 30
    Adjustment::factory()->forInvoice($invoice)->fee()->fixed()->create(['amount' => 20, 'is_percent' => false]);

    app(SyncInvoiceAdjustments::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->discount_amount)->toBe(80.0); // 50 + 30
    expect($invoice->fresh()->fee_amount)->toBe(20.0);
});

it('calculates discount_amount as zero when no discount adjustments exist', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['discount_amount' => 500]);

    // Create only fee adjustments
    Adjustment::factory()->forInvoice($invoice)->fee()->fixed()->create(['amount' => 100, 'is_percent' => false]);

    // Act
    app(SyncInvoiceAdjustments::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->discount_amount)->toBe(0.0);
});

it('calculates fee_amount as zero when no fee adjustments exist', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['fee_amount' => 500]);

    // Create only discount adjustments
    Adjustment::factory()->forInvoice($invoice)->discount()->fixed()->create(['amount' => 100, 'is_percent' => false]);

    // Act
    app(SyncInvoiceAdjustments::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->fee_amount)->toBe(0.0);
});
