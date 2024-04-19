<?php

use App\Models\Company;

it('can determine if company is mailable', function () {
    $company = Company::factory()->create();

    expect($company->isMailable())->toBeTrue();
    expect($company->email)->not->toBeNull();

    // remove email
    $company->email = null;

    expect($company->isMailable())->toBeFalse();
});

it('can determine if company is callable', function () {
    $company = Company::factory()->create();

    expect($company->isCallable())->toBeTrue();
    expect($company->phone)->not->toBeNull();

    // remove phone number
    $company->phone = null;

    expect($company->isCallable())->toBeFalse();
});

it('can determine if company is contactable', function () {
    $company = Company::factory()->create();

    expect($company->isContactable())->toBeTrue();
    expect($company->email)->not->toBeNull();
    expect($company->phone)->not->toBeNull();

    // remove email
    $company->email = null;

    expect($company->isContactable())->toBeTrue();

    // remove phone number
    $company->phone = null;

    expect($company->isContactable())->toBeFalse();
});

describe('scopes', function () {
    beforeEach(function () {
        Company::factory()->create();
        Company::factory()->withoutEmail()->create();
        Company::factory()->withoutPhone()->create();
        Company::factory()->withoutEmail()->withoutPhone()->create();
    });

    it('can filter companies by mailable scope', function () {
        expect(Company::mailable()->count())->toBe(2);
        expect(Company::mailable()->first()->isMailable())->toBeTrue();
    });

    it('can filter companies by callable scope', function () {
        expect(Company::callable()->count())->toBe(2);
        expect(Company::callable()->first()->isCallable())->toBeTrue();
    });

    it('can filter companies by contactable scope', function () {
        expect(Company::contactable()->count())->toBe(3);
        expect(Company::contactable()->first()->isContactable())->toBeTrue();
    });
});
