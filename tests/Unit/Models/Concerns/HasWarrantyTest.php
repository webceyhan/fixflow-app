<?php

use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('hasWarrantyModels', [
    'Device' => [Device::class],
]);

it('can determine if model has warranty', function (string $modelClass) {
    // Arrange
    $withWarranty = $modelClass::factory()->create(['warranty_expire_date' => now()->addYear()]);
    $expiredWarranty = $modelClass::factory()->create(['warranty_expire_date' => now()->subYear()]);
    $noWarranty = $modelClass::factory()->create(['warranty_expire_date' => null]);

    // Assert
    expect($withWarranty->hasWarranty())->toBeTrue();
    expect($expiredWarranty->hasWarranty())->toBeFalse();
    expect($noWarranty->hasWarranty())->toBeFalse();
})->with('hasWarrantyModels');

it('can filter models with warranty scope', function (string $modelClass) {
    // Arrange
    $withWarranty = $modelClass::factory()->create(['warranty_expire_date' => now()->addYear()]);
    $expiredWarranty = $modelClass::factory()->create(['warranty_expire_date' => now()->subYear()]);
    $noWarranty = $modelClass::factory()->create(['warranty_expire_date' => null]);

    // Act
    $models = $modelClass::withWarranty()->get();

    // Assert
    expect($models)->toHaveCount(1);
    expect($models->first()->id)->toBe($withWarranty->id);
})->with('hasWarrantyModels');
