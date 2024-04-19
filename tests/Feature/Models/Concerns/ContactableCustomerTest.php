<?php

use App\Models\Customer;

it('cam determine if customer is mailable', function () {
    $customer = Customer::factory()->create();

    expect($customer->isMailable())->toBeTrue();
    expect($customer->email)->not->toBeNull();

    // remove email
    $customer->email = null;

    expect($customer->isMailable())->toBeFalse();
});

it('can determine if customer is callable', function () {
    $customer = Customer::factory()->create();

    expect($customer->isCallable())->toBeTrue();
    expect($customer->phone)->not->toBeNull();

    // remove phone number
    $customer->phone = null;

    expect($customer->isCallable())->toBeFalse();
});

it('can determine if customer is contactable', function () {
    $customer = Customer::factory()->create();

    expect($customer->isContactable())->toBeTrue();
    expect($customer->email)->not->toBeNull();
    expect($customer->phone)->not->toBeNull();

    // remove email
    $customer->email = null;

    expect($customer->isContactable())->toBeTrue();

    // remove phone number
    $customer->phone = null;

    expect($customer->isContactable())->toBeFalse();
});

describe('scopes', function () {
    beforeEach(function () {
        Customer::factory()->create();
        Customer::factory()->withoutEmail()->create();
        Customer::factory()->withoutPhone()->create();
        Customer::factory()->withoutEmail()->withoutPhone()->create();
    });

    it('can filter customers by mailable scope', function () {
        expect(Customer::mailable()->count())->toBe(2);
        expect(Customer::mailable()->first()->isMailable())->toBeTrue();
    });

    it('can filter customers by callable scope', function () {
        expect(Customer::callable()->count())->toBe(2);
        expect(Customer::callable()->first()->isCallable())->toBeTrue();
    });

    it('can filter customers by contactable scope', function () {
        expect(Customer::contactable()->count())->toBe(3);
        expect(Customer::contactable()->first()->isContactable())->toBeTrue();
    });
});
