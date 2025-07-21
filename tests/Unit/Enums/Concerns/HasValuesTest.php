<?php

use App\Enums\AdjustmentReason;
use App\Enums\AdjustmentType;
use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Enums\InvoiceStatus;
use App\Enums\OrderStatus;
use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Enums\TicketStatus;
use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Enums\UserRole;
use App\Enums\UserStatus;

dataset('enums', [
    'AdjustmentReason' => [AdjustmentReason::class],
    'AdjustmentType' => [AdjustmentType::class],
    'DeviceStatus' => [DeviceStatus::class],
    'DeviceType' => [DeviceType::class],
    'InvoiceStatus' => [InvoiceStatus::class],
    'OrderStatus' => [OrderStatus::class],
    'Priority' => [Priority::class],
    'TaskStatus' => [TaskStatus::class],
    'TaskType' => [TaskType::class],
    'TicketStatus' => [TicketStatus::class],
    'TransactionMethod' => [TransactionMethod::class],
    'TransactionType' => [TransactionType::class],
    'UserRole' => [UserRole::class],
    'UserStatus' => [UserStatus::class],
]);

it('provides string values for the enum', function (string $enumClass) {
    // Arrange
    $values = $enumClass::values();

    // Assert
    expect($values)->toBeArray();
    expect($values)->toHaveCount(count($enumClass::cases()));
})->with('enums');
