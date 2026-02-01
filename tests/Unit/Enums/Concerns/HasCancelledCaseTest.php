<?php

use App\Enums\InvoiceStatus;
use App\Enums\OrderStatus;
use App\Enums\TaskStatus;
use App\Enums\TicketStatus;

dataset('enums', [
    'TaskStatus' => TaskStatus::class,
    'OrderStatus' => OrderStatus::class,
    'InvoiceStatus' => InvoiceStatus::class,
    'TicketStatus' => TicketStatus::class,
]);

it('detects cancelled case correctly', function (string $enumClass) {
    // Arrange
    $cancelledCase = $enumClass::Cancelled;
    $nonCancelledCases = array_filter(
        $enumClass::cases(),
        fn ($case) => $case !== $cancelledCase
    );

    // Assert
    expect($cancelledCase->isCancelled())->toBeTrue();

    foreach ($nonCancelledCases as $case) {
        expect($case->isCancelled())->toBeFalse();
    }
})->with('enums');
