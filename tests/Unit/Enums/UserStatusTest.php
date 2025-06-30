<?php

use App\Enums\UserStatus;

it('has values', function () {
    expect(UserStatus::values())->toBe([
        'active',
        'suspended',
        'terminated',
    ]);
});

it('can determine if case is active', function (UserStatus $case, bool $expected) {
    expect($case->isActive())->toBe($expected);
})->with([
    [UserStatus::Active, true],
    [UserStatus::Suspended, false],
    [UserStatus::Terminated, false],
]);
