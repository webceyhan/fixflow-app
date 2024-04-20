<?php

use App\Models\Company;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('can view all companies', function () {
    $user = User::factory()->create();
    $companies = Company::factory(2)->create();

    $response = $this->actingAs($user)->get('/companies');

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Companies/Index')
                ->has('companies', 2)
                ->has(
                    'companies.0',
                    fn (Assert $page) => $page
                        ->where('id', $companies->first()->id)
                        ->where('name', $companies->first()->name)
                        ->where('email', $companies->first()->email)
                        ->where('phone', $companies->first()->phone)
                        ->where('vat_number', $companies->first()->vat_number)
                        ->etc()
                )
        );
});

it('can view a company', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create();

    $response = $this->actingAs($user)->get('/companies/' . $company->id);

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Companies/Show')
                ->has(
                    'company',
                    fn (Assert $page) => $page
                        ->where('id', $company->id)
                        ->where('name', $company->name)
                        ->where('email', $company->email)
                        ->where('phone', $company->phone)
                        ->where('vat_number', $company->vat_number)
                        ->etc()
                )
        );
});
