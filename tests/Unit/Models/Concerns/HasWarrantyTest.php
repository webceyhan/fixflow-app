<?php

use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('models', [
    'Device' => [Device::class],
]);

it('initializes model properties correctly', function (string $modelClass) {
    // Arrange
    $model = new $modelClass;

    // Assert
    expect($model->getCasts())->toHaveKey('warranty_expire_date', 'date');
})->with('models');

it('can determine if model has warranty', function (string $modelClass) {
    // Arrange
    $model = $modelClass::factory()->withWarranty()->create();

    // Assert
    expect($model->hasWarranty())->toBeTrue();
    expect($model->warranty_expire_date->isFuture())->toBeTrue();
})->with('models');

it('can filter records by with warranty scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory(2)->withWarranty()->create();
    $modelClass::factory(1)->withoutWarranty()->create();

    // Act
    $warrantyModels = $modelClass::query()->withWarranty()->get();

    // Assert
    expect($warrantyModels)->toHaveCount(2);
    expect($warrantyModels->first()->hasWarranty())->toBeTrue();
})->with('models');
