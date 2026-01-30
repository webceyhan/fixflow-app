<?php

use App\Enums\CustomerStatus;
use App\Enums\DeviceStatus;
use App\Enums\TaskStatus;
use App\Enums\TicketStatus;
use App\Enums\UserStatus;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('hasActiveStatusModels', [
    'User' => [User::class, UserStatus::class],
    'Customer' => [Customer::class, CustomerStatus::class],
    'Device' => [Device::class, DeviceStatus::class],
    'Ticket' => [Ticket::class, TicketStatus::class],
    'Task' => [Task::class, TaskStatus::class],
]);

it('can determine if model is active', function (string $modelClass, string $enumClass) {
    // Arrange
    $cases = $enumClass::all();
    // assuming first case is always active and last is not.
    $activeModel = $modelClass::factory()->ofStatus($cases->first())->create();
    $notActiveModel = $modelClass::factory()->ofStatus($cases->last())->create();

    // Assert
    expect($activeModel->isActive())->toBeTrue();
    expect($notActiveModel->isActive())->toBeFalse();
})->with('hasActiveStatusModels');

it('can filter models by active scope', function (string $modelClass, string $enumClass) {
    // Arrange
    $cases = $enumClass::all();
    // assuming first case is always active and last is not.
    $activeModel = $modelClass::factory()->ofStatus($cases->first())->create();
    $notActiveModel = $modelClass::factory()->ofStatus($cases->last())->create();

    // Act
    $models = $modelClass::active()->get();

    // Assert
    expect($models)->toHaveCount(1);
    expect($models->first()->id)->toBe($activeModel->id);
})->with('hasActiveStatusModels');

it('can filter models by not-active scope', function (string $modelClass, string $enumClass) {
    // Arrange
    $cases = $enumClass::all();
    // assuming first case is always active and last is not.
    $activeModel = $modelClass::factory()->ofStatus($cases->first())->create();
    $notActiveModel = $modelClass::factory()->ofStatus($cases->last())->create();

    // Act
    $models = $modelClass::notActive()->get();

    // Assert
    expect($models)->toHaveCount(1);
    expect($models->first()->id)->toBe($notActiveModel->id);
})->with('hasActiveStatusModels');
