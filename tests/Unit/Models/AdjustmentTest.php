<?php

use App\Enums\AdjustmentReason;
use App\Enums\AdjustmentType;
use App\Models\Adjustment;
use App\Models\Invoice;
use App\Observers\AdjustmentObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Adjustment::class,
    observers: [
        AdjustmentObserver::class,
    ],
    defaults: [
        'type' => AdjustmentType::Bonus,
        'reason' => AdjustmentReason::Welcome,
    ],
    fillables: [
        'amount',
        'percentage',
        'note',
        'type',
        'reason',
    ],
    casts: [
        'amount' => 'float',
        'percentage' => 'float',
        'type' => AdjustmentType::class,
        'reason' => AdjustmentReason::class,
    ],
    relations: [
        'invoice' => BelongsTo::class,
    ]
);

// RELATION TESTS //////////////////////////////////////////////////////////////////////////////////

it('belongs to invoice relationship', function () {
    // Arrange
    $invoice = Invoice::factory()->create();
    $adjustment = Adjustment::factory()->forInvoice($invoice)->create();

    // Assert
    expect($adjustment->invoice)->toBeInstanceOf(Invoice::class);
    expect($adjustment->invoice->id)->toBe($invoice->id);
});

// SCOPE TESTS /////////////////////////////////////////////////////////////////////////////////////

it('can filter by type scope', function (AdjustmentType $type) {
    // Arrange
    $adjustment1 = Adjustment::factory()->ofType($type)->create();
    $adjustment2 = Adjustment::factory()->ofType($type->next())->create();

    // Act
    $adjustments = Adjustment::ofType($type)->get();

    // Assert
    expect($adjustments)->toHaveCount(1);
    expect($adjustments->first()->type)->toBe($type);
    expect($adjustments->first()->id)->toBe($adjustment1->id);
})->with(AdjustmentType::cases());

it('can filter by reason scope', function (AdjustmentReason $reason) {
    // Arrange
    $adjustment1 = Adjustment::factory()->ofReason($reason)->create();
    $adjustment2 = Adjustment::factory()->ofReason($reason->next())->create();

    // Act
    $adjustments = Adjustment::ofReason($reason)->get();

    // Assert
    expect($adjustments)->toHaveCount(1);
    expect($adjustments->first()->reason)->toBe($reason);
    expect($adjustments->first()->id)->toBe($adjustment1->id);
})->with(AdjustmentReason::cases());

// METHOD TESTS ////////////////////////////////////////////////////////////////////////////////////

it('can determine if adjustment is fixed amount', function () {
    // Arrange
    $fixedAdjustment = Adjustment::factory()->withAmount(100.0)->create();
    $percentageAdjustment = Adjustment::factory()->withPercentage(10.0)->create();

    // Assert
    expect($fixedAdjustment->isFixed())->toBeTrue();
    expect($percentageAdjustment->isFixed())->toBeFalse();
});

it('can determine if adjustment is additive', function () {
    // Arrange
    $feeAdjustment = Adjustment::factory()->ofReason(AdjustmentReason::Service)->create();
    $discountAdjustment = Adjustment::factory()->ofReason(AdjustmentReason::Promotion)->create();

    // Assert
    expect($feeAdjustment->isAddition())->toBeTrue();
    expect($discountAdjustment->isAddition())->toBeFalse();
});

it('can calculate effective amount', function (AdjustmentReason $reason, ?float $amount, ?float $percentage, float $result) {
    // Arrange
    $subtotal = 1000.0;
    $adjustment = Adjustment::factory()->ofReason($reason)->create([
        'amount' => $amount ?? 0,
        'percentage' => $percentage,
    ]);

    // Act & Assert
    expect($adjustment->getEffectiveAmount($subtotal))->toBe($result);
})->with([
    'fixed fee' => [AdjustmentReason::Service, 50.0, null, 50.0], // additive
    'fixed bonus' => [AdjustmentReason::Welcome, 10.0, null, -10.0], // reductive
    'fixed discount' => [AdjustmentReason::Promotion, 25.0, null, -25.0], // reductive
    'relative fee' => [AdjustmentReason::RushService, null, 10.0, 100.0], // additive 10% of 1000
    'relative compensation' => [AdjustmentReason::ServiceDelay, null, 15.0, -150.0], // reductive 15% of 1000
    'zero relative' => [AdjustmentReason::Promotion, 0.0, 0.0, 0.0], // 0.0
]);
