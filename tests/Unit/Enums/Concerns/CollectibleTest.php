<?php

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Support\Collection;

dataset('enums', [
    'UserRole' => [UserRole::class],
    'UserStatus' => [UserStatus::class],
]);

it('provides collection of cases for the enum', function (string $enumClass) {
    // Arrange
    $values = $enumClass::all();

    // Assert
    expect($values)->toBeInstanceOf(Collection::class);
    expect($values)->toHaveCount(count($enumClass::cases()));
})->with('enums');
