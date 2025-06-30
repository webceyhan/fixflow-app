<?php

use App\Enums\UserRole;

it('has values', function () {
    expect(UserRole::values())->toBe([
        'admin',
        'manager',
        'technician',
    ]);
});

it('can determine if case is admin', function (UserRole $case, bool $expected) {
    expect($case->isAdmin())->toBe($expected);
})->with([
    [UserRole::Admin, true],
    [UserRole::Manager, false],
    [UserRole::Technician, false],
]);
