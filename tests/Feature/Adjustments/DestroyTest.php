<?php

declare(strict_types=1);

use App\Enums\AdjustmentReason;
use App\Enums\AdjustmentType;
use App\Models\Adjustment;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseMissing;

it('deletes an adjustment successfully', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $adjustment = Adjustment::factory()->create();
    $adjustmentId = $adjustment->id;
    $invoiceId = $adjustment->invoice_id;

    // Act
    $response = actingAs($manager)->delete(route('adjustments.destroy', $adjustment));

    // Assert
    $response->assertRedirect(route('invoices.show', $invoiceId));
    assertDatabaseMissing('adjustments', ['id' => $adjustmentId]);
});

it('syncs invoice discount amount after deleting a discount adjustment', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $adjustment = Adjustment::factory()->create([
        'amount' => 50,
        'is_percent' => false,
        'reason' => AdjustmentReason::Compensation,
        'type' => AdjustmentType::Discount,
    ]);
    $adjustment->invoice->update([
        'task_total' => 200,
        'order_total' => 100,
        'discount_amount' => 50,
    ]);
    $invoice = $adjustment->invoice;

    // Act
    actingAs($manager)->delete(route('adjustments.destroy', $adjustment));

    // Assert
    $invoice->refresh();
    expect($invoice->discount_amount)->toBe(0.0);
});

it('syncs invoice fee amount after deleting a fee adjustment', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $adjustment = Adjustment::factory()->create([
        'amount' => 25,
        'is_percent' => false,
        'reason' => AdjustmentReason::RushService,
        'type' => AdjustmentType::Fee,
    ]);
    $adjustment->invoice->update([
        'task_total' => 200,
        'order_total' => 100,
        'fee_amount' => 25,
    ]);
    $invoice = $adjustment->invoice;

    // Act
    actingAs($manager)->delete(route('adjustments.destroy', $adjustment));

    // Assert
    $invoice->refresh();
    expect($invoice->fee_amount)->toBe(0.0);
});

it('recalculates invoice amounts when other adjustments exist', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $adjustment = Adjustment::factory()->create([
        'amount' => 50,
        'is_percent' => false,
        'reason' => AdjustmentReason::Compensation,
        'type' => AdjustmentType::Discount,
    ]);
    $adjustment->invoice->update([
        'task_total' => 200,
        'order_total' => 100,
    ]);
    $otherAdjustment = Adjustment::factory()->create([
        'invoice_id' => $adjustment->invoice_id,
        'amount' => 30,
        'is_percent' => false,
        'reason' => AdjustmentReason::Loyalty,
        'type' => AdjustmentType::Discount,
    ]);
    $adjustment->invoice->update(['discount_amount' => 80]);
    $invoice = $adjustment->invoice;

    // Act
    actingAs($manager)->delete(route('adjustments.destroy', $adjustment));

    // Assert
    $invoice->refresh();
    expect($invoice->discount_amount)->toBe(30.0);
});

it('redirects to invoice show page after successful deletion', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $adjustment = Adjustment::factory()->create();
    $invoiceId = $adjustment->invoice_id;

    // Act
    $response = actingAs($manager)->delete(route('adjustments.destroy', $adjustment));

    // Assert
    $response->assertRedirect(route('invoices.show', $invoiceId));
});
