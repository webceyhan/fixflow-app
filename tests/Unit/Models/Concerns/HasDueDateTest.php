<?php

use App\Models\Invoice;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('models', [
    'Ticket' => [Ticket::class],
    'Invoice' => [Invoice::class],
]);

it('initializes model properties correctly', function (string $modelClass) {
    // Arrange
    $model = new $modelClass;

    // Assert
    expect($model->getCasts())->toHaveKey('due_date', 'date');
})->with('models');

it('can determine if model is overdue', function (string $modelClass) {
    // Arrange
    $pendingModel = $modelClass::factory()->overdue()->pending()->create();
    $completedModel = $modelClass::factory()->overdue()->complete()->create();

    // Assert
    expect($pendingModel->isOverdue())->toBeTrue();
    expect($pendingModel->due_date->isPast())->toBeTrue();
    expect($completedModel->isOverdue())->toBeFalse();
    expect($completedModel->due_date->isPast())->toBeTrue();
})->with('models');

it('can filter records by overdue scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory(2)->overdue()->create();
    $modelClass::factory(1)->dueInDays()->create();

    // Act
    $overdueModels = $modelClass::query()->overdue()->get();

    // Assert
    expect($overdueModels)->toHaveCount(2);
    expect($overdueModels->first()->isOverdue())->toBeTrue();
})->with('models');

it('can filter records by not overdue scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory(2)->dueInDays()->create();
    $modelClass::factory(1)->overdue()->create();

    // Act
    $notOverdueModels = $modelClass::query()->notOverdue()->get();

    // Assert
    expect($notOverdueModels)->toHaveCount(2);
    expect($notOverdueModels->first()->isOverdue())->toBeFalse();
})->with('models');
