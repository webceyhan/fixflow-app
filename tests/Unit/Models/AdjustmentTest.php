<?php

use App\Enums\AdjustmentReason;
use App\Enums\AdjustmentType;
use App\Models\Adjustment;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates an adjustment with valid attributes', function () {
    // Arrange & Act
    $adjustment = Adjustment::factory()->create();

    // Assert
    expect($adjustment->invoice_id)->not->toBeNull();
    expect($adjustment->amount)->toBeGreaterThan(0);
    expect($adjustment->type)->toBeInstanceOf(AdjustmentType::class);
    expect($adjustment->reason)->toBeInstanceOf(AdjustmentReason::class);
});

it('can update an adjustment', function () {
    // Arrange
    $adjustment = Adjustment::factory()->create();

    // Act
    $adjustment->update([
        'amount' => 200.0,
        'percentage' => 10.0,
        'note' => 'Updated note',
        'type' => AdjustmentType::Discount,
        'reason' => AdjustmentReason::Promotion,
    ]);

    // Assert
    expect($adjustment->amount)->toBe(200.0);
    expect($adjustment->percentage)->toBe(10.0);
    expect($adjustment->note)->toBe('Updated note');
    expect($adjustment->type)->toBe(AdjustmentType::Discount);
    expect($adjustment->reason)->toBe(AdjustmentReason::Promotion);
});

it('can delete an adjustment', function () {
    // Arrange
    $adjustment = Adjustment::factory()->create();

    // Act
    $adjustment->delete();

    // Assert
    expect(Adjustment::find($adjustment->id))->toBeNull();
});

it('belongs to an invoice', function () {
    // Arrange & Act
    $invoice = Invoice::factory()->create();
    $adjustment = Adjustment::factory()->forInvoice($invoice)->create();

    // Assert
    expect($adjustment->invoice_id)->toBe($invoice->id);
    expect($adjustment->invoice->id)->toBe($invoice->id);
});

it('can filter adjustments by reason scope', function (AdjustmentReason $reason) {
    // Arrange
    Adjustment::factory()->ofReason($reason)->create();

    // Act
    $adjustments = Adjustment::ofReason($reason)->get();

    // Assert
    expect($adjustments)->toHaveCount(1);
    expect($adjustments->first()->reason)->toBe($reason);
})->with(AdjustmentReason::cases());

it('correctly identifies fixed amount adjustments', function () {
    // Arrange
    $fixedAdjustment = Adjustment::factory()->withAmount(100.0)->create();
    $percentageAdjustment = Adjustment::factory()->withPercentage(10.0)->create();

    // Assert
    expect($fixedAdjustment->isFixed())->toBeTrue();
    expect($percentageAdjustment->isFixed())->toBeFalse();
});

it('correctly identifies additive adjustments', function () {
    // Arrange
    $feeAdjustment = Adjustment::factory()->ofReason(AdjustmentReason::Service)->create();
    $discountAdjustment = Adjustment::factory()->ofReason(AdjustmentReason::Promotion)->create();

    // Assert
    expect($feeAdjustment->isAddition())->toBeTrue();
    expect($discountAdjustment->isAddition())->toBeFalse();
});

it('calculates effective amount for fixed adjustments', function () {
    // Arrange
    $subtotal = 1000.0;
    $feeAdjustment = Adjustment::factory()->ofReason(AdjustmentReason::Service)->withAmount(50.0)->create();
    $discountAdjustment = Adjustment::factory()->ofReason(AdjustmentReason::Promotion)->withAmount(25.0)->create();

    // Act & Assert
    expect($feeAdjustment->getEffectiveAmount($subtotal))->toBe(50.0);
    expect($discountAdjustment->getEffectiveAmount($subtotal))->toBe(25.0);
});

it('calculates effective amount for percentage adjustments', function () {
    // Arrange
    $subtotal = 1000.0;
    $feeAdjustment = Adjustment::factory()->ofReason(AdjustmentReason::RushService)->withPercentage(10.0)->create();
    $discountAdjustment = Adjustment::factory()->ofReason(AdjustmentReason::Promotion)->withPercentage(15.0)->create();

    // Act & Assert
    expect($feeAdjustment->getEffectiveAmount($subtotal))->toBe(100.0); // 10% of 1000, additive
    expect($discountAdjustment->getEffectiveAmount($subtotal))->toBe(-150.0); // 15% of 1000, subtractive
});

it('handles zero percentage correctly', function () {
    // Arrange
    $subtotal = 1000.0;
    $adjustment = Adjustment::factory()->ofReason(AdjustmentReason::Promotion)->withPercentage(0.0)->create();

    // Act & Assert
    expect($adjustment->getEffectiveAmount($subtotal))->toBe(0.0);
});

it('handles zero subtotal for percentage adjustments', function () {
    // Arrange
    $subtotal = 0.0;
    $adjustment = Adjustment::factory()->ofReason(AdjustmentReason::Promotion)->withPercentage(10.0)->create();

    // Act & Assert
    expect($adjustment->getEffectiveAmount($subtotal))->toBe(0.0);
});
