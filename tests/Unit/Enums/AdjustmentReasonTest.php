<?php

use App\Enums\AdjustmentReason;
use App\Enums\AdjustmentType;

dataset('discountReasons', [
    'bulk service' => [AdjustmentReason::BulkService, AdjustmentType::Discount],
    'promotion' => [AdjustmentReason::Promotion, AdjustmentType::Discount],
    'loyalty' => [AdjustmentReason::Loyalty, AdjustmentType::Discount],
    'welcome' => [AdjustmentReason::Welcome, AdjustmentType::Discount],
    'compensation' => [AdjustmentReason::Compensation, AdjustmentType::Discount],
]);

dataset('feeReasons', [
    'rush service' => [AdjustmentReason::RushService, AdjustmentType::Fee],
    'delivery' => [AdjustmentReason::Delivery, AdjustmentType::Fee],
    'late payment' => [AdjustmentReason::LatePayment, AdjustmentType::Fee],
    'cancellation' => [AdjustmentReason::Cancellation, AdjustmentType::Fee],
]);

it('returns correct type for discount reasons', function (AdjustmentReason $reason, AdjustmentType $expectedType) {
    // Act
    $result = $reason->type();

    // Assert
    expect($result)->toBe($expectedType);
})->with('discountReasons');

it('returns correct type for fee reasons', function (AdjustmentReason $reason, AdjustmentType $expectedType) {
    // Act
    $result = $reason->type();

    // Assert
    expect($result)->toBe($expectedType);
})->with('feeReasons');

it('returns all discount cases when filtering by discount type', function () {
    // Act
    $result = AdjustmentReason::casesByType(AdjustmentType::Discount);

    // Assert
    expect($result)->toHaveCount(5)
        ->and($result)->toContain(
            AdjustmentReason::BulkService,
            AdjustmentReason::Promotion,
            AdjustmentReason::Loyalty,
            AdjustmentReason::Welcome,
            AdjustmentReason::Compensation
        );
});

it('returns all fee cases when filtering by fee type', function () {
    // Act
    $result = AdjustmentReason::casesByType(AdjustmentType::Fee);

    // Assert
    expect($result)->toHaveCount(4)
        ->and($result)->toContain(
            AdjustmentReason::RushService,
            AdjustmentReason::Delivery,
            AdjustmentReason::LatePayment,
            AdjustmentReason::Cancellation
        );
});
