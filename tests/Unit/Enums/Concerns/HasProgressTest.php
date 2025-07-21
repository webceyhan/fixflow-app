<?php

use App\Enums\DeviceStatus;
use App\Enums\InvoiceStatus;
use App\Enums\OrderStatus;
use App\Enums\TaskStatus;
use App\Enums\TicketStatus;

dataset('enums', [ // pending count, complete count
    'DeviceStatus' => [DeviceStatus::class, 3, 2],
    'InvoiceStatus' => [InvoiceStatus::class, 3, 2],
    'OrderStatus' => [OrderStatus::class, 2, 1],
    'TaskStatus' => [TaskStatus::class, 1, 1],
    'TicketStatus' => [TicketStatus::class, 3, 2],
]);

it('provides pending and complete methods for the enum', function (string $enumClass, int $pendingCount, int $completeCount) {
    // Arrange
    $pendingCases = $enumClass::pendingCases();
    $completeCases = $enumClass::completeCases();

    // Assert
    expect($pendingCases)->toHaveCount($pendingCount);
    expect(reset($pendingCases)->isPending())->toBeTrue();

    expect($completeCases)->toHaveCount($completeCount);
    expect(reset($completeCases)->isComplete())->toBeTrue();
})->with('enums');
