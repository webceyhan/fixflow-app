<?php

use App\Enums\UserStatus;

dataset('enums', [
    'UserStatus' => [UserStatus::class, 1],
]);

it('provides active methods for the enum', function (string $enumClass, int $activeCount) {
    // Arrange
    $cases = $enumClass::cases();
    $activeCases = $enumClass::activeCases();
    $inactiveCase = end($cases); // last one is always inactive

    // Assert
    expect($activeCases)->toHaveCount($activeCount);
    expect($inactiveCase->isActive())->toBeFalse();

    foreach ($activeCases as $case) {
        expect($case->isActive())->toBeTrue();
    }
})->with('enums');
