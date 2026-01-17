<?php

use App\Enums\UserRole;
use App\Enums\UserStatus;

dataset('enums', [
    'UserRole' => [UserRole::class],
    'UserStatus' => [UserStatus::class],
]);

it('provides next case for the enum correctly', function (string $enumClass) {
    // Arrange
    $cases = $enumClass::cases();
    $last = end($cases);
    [$first, $second] = $cases;

    // Assert
    expect($first->next())->toBe($second);
    expect($last->next())->toBe($first); // Circular next
})->with('enums');
