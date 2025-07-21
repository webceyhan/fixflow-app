<?php

use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('models', [
    'Device' => [Device::class],
]);

it('initializes model properties correctly', function (string $modelClass) {
    expect($modelClass)->toCastAttributes([
        'warranty_expire_date' => 'date',
    ]);
})->with('models');

it('can determine if model has warranty', function (string $modelClass) {
    // Arrange
    $modelWithWarranty = $modelClass::factory()->withWarranty()->create();
    $modelWithoutWarranty = $modelClass::factory()->withoutWarranty()->create();

    // Assert
    expect($modelWithWarranty->hasWarranty())->toBeTrue();
    expect($modelWithWarranty->warranty_expire_date->isFuture())->toBeTrue();

    expect($modelWithoutWarranty->hasWarranty())->toBeFalse();
    expect($modelWithoutWarranty->warranty_expire_date)->toBeNull();
})->with('models');

it('can filter by warranty scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory()->withWarranty()->create();
    $modelClass::factory()->withoutWarranty()->create();

    // Act
    $warrantyModels = $modelClass::query()->withWarranty()->get();

    // Assert
    expect($warrantyModels)->toHaveCount(1);
    expect($warrantyModels->first()->hasWarranty())->toBeTrue();
})->with('models');
