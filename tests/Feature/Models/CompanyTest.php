<?php

use App\Models\Company;
use Illuminate\Support\Carbon;

it('can initialize company', function () {
    $company = new Company();

    expect($company->id)->toBeNull();
    expect($company->name)->toBeNull();
    expect($company->email)->toBeNull();
    expect($company->phone)->toBeNull();
    expect($company->vat_number)->toBeNull();
    expect($company->created_at)->toBeNull();
    expect($company->updated_at)->toBeNull();
});

it('can create company', function () {
    $company = Company::factory()->create();

    expect($company->id)->toBeInt();
    expect($company->name)->toBeString();
    expect($company->email)->toBeString();
    expect($company->phone)->toBeString();
    expect($company->vat_number)->toBeString();
    expect($company->created_at)->toBeInstanceOf(Carbon::class);
    expect($company->updated_at)->toBeInstanceOf(Carbon::class);
});

it('can create company without email address', function () {
    $company = Company::factory()->withoutEmail()->create();

    expect($company->email)->toBeNull();
});

it('can create company without phone number', function () {
    $company = Company::factory()->withoutPhone()->create();

    expect($company->phone)->toBeNull();
});

it('can create company without VAT number', function () {
    $company = Company::factory()->withoutVatNumber()->create();

    expect($company->vat_number)->toBeNull();
});

it('can update company', function () {
    $company = Company::factory()->create();

    $company->update([
        'name' => 'Microsoft Corporation',
        'email' => 'info@microsoft.com',
        'phone' => '+1234567890',
        'vat_number' => '1234567890',
    ]);

    expect($company->name)->toBe('Microsoft Corporation');
    expect($company->email)->toBe('info@microsoft.com');
    expect($company->phone)->toBe('+1234567890');
    expect($company->vat_number)->toBe('1234567890');
});

it('can delete company', function () {
    $company = Company::factory()->create();

    $company->delete();

    expect(Company::find($company->id))->toBeNull();
});

