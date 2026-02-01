<?php

use App\Enums\AdjustmentReason;
use App\Enums\Attributes\Active;
use App\Enums\Attributes\Tag;
use App\Enums\DeviceStatus;
use App\Enums\InvoiceStatus;
use App\Enums\OrderStatus;
use App\Enums\TaskStatus;
use App\Enums\TicketStatus;

dataset('enums', [
    'AdjustmentReason' => [AdjustmentReason::class, Tag::class],
    'DeviceStatus' => [DeviceStatus::class, Active::class],
    'InvoiceStatus' => [InvoiceStatus::class, Active::class],
    'OrderStatus' => [OrderStatus::class, Active::class],
    'TaskStatus' => [TaskStatus::class, Active::class],
    'TicketStatus' => [TicketStatus::class, Active::class],
]);

it('provides attribute support for enums', function (string $enumClass, string $attributeClass) {
    // Arrange
    $cases = $enumClass::cases();
    $casesByAttribute = $enumClass::casesByAttribute($attributeClass);

    // Assert
    expect(count($casesByAttribute))->toBeGreaterThan(0);
    expect(count($casesByAttribute))->toBeLessThanOrEqual(count($cases));

    expect($casesByAttribute[0]->attribute($attributeClass))->not->toBeNull();

    expect($casesByAttribute[0]->hasAttribute($attributeClass))->toBeTrue();
})->with('enums');
