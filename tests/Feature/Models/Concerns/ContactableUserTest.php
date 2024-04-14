<?php

use App\Models\User;

/**
 * User email is required, so we can't create a user without email,
 * thus user is always mailable and contactable.
 */

it('cam determine if user is mailable', function () {
    $user = User::factory()->create();

    expect($user->isMailable())->toBeTrue();
    expect($user->email)->not->toBeNull();
});

it('can determine if user is callable', function () {
    $user = User::factory()->create();

    expect($user->isCallable())->toBeTrue();
    expect($user->phone)->not->toBeNull();

    // remove phone number
    $user->phone = null;

    expect($user->isCallable())->toBeFalse();
});

it('can determine if user is contactable', function () {
    $user = User::factory()->create();

    expect($user->isContactable())->toBeTrue();
    expect($user->email)->not->toBeNull();
    expect($user->phone)->not->toBeNull();

    // remove phone number
    $user->phone = null;

    expect($user->isContactable())->toBeTrue();
});

describe('scopes', function () {
    beforeEach(function () {
        User::factory()->create();
        User::factory()->withoutPhone()->create();
    });

    it('can filter users by callable scope', function () {
        expect(User::callable()->count())->toBe(1);
        expect(User::callable()->first()->isCallable())->toBeTrue();
    });

    it('can filter users by contactable scope', function () {
        expect(User::contactable()->count())->toBe(2);
        expect(User::contactable()->first()->isContactable())->toBeTrue();
    });
});
