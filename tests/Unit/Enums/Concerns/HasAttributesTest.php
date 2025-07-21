<?php

use App\Enums\AdjustmentReason;
use App\Enums\Attributes\Pending;
use App\Enums\Attributes\Type;
use App\Enums\DeviceStatus;
use App\Enums\InvoiceStatus;
use App\Enums\OrderStatus;
use App\Enums\TaskStatus;
use App\Enums\TicketStatus;

dataset('enums', [
    'AdjustmentReason' => [AdjustmentReason::class, Type::class],
    'DeviceStatus' => [DeviceStatus::class, Pending::class],
    'InvoiceStatus' => [InvoiceStatus::class, Pending::class],
    'OrderStatus' => [OrderStatus::class, Pending::class],
    'TaskStatus' => [TaskStatus::class, Pending::class],
    'TicketStatus' => [TicketStatus::class, Pending::class],
]);

it('provides attribute support for enums', function (string $enumClass, string $attributeClass) {
    // Arrange
    $cases = $enumClass::cases();
    $casesByAttribute = $enumClass::casesByAttribute($attributeClass);

    // Assert
    expect(count($casesByAttribute))->toBeGreaterThan(0);
    expect(count($casesByAttribute))->toBeLessThanOrEqual(count($cases));

    expect($casesByAttribute[0]->attributes())->toBeArray();
    expect($casesByAttribute[0]->attributes($attributeClass))->toBeArray();

    expect($casesByAttribute[0]->attribute($attributeClass))->not->toBeNull();

    expect($casesByAttribute[0]->hasAttribute($attributeClass))->toBeTrue();
})->with('enums');
