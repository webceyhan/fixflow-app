<?php

use App\Models\Customer;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('can view all customers', function () {
    $user = User::factory()->create();
    $customers = Customer::factory(2)->create();

    $response = $this->actingAs($user)->get('/customers');

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Customers/Index')
                ->has('customers', 2)
                ->has(
                    'customers.0',
                    fn (Assert $page) => $page
                        ->where('id', $customers->first()->id)
                        ->where('name', $customers->first()->name)
                        ->where('email', $customers->first()->email)
                        ->where('phone', $customers->first()->phone)
                        ->where('address', $customers->first()->address)
                        ->where('note', $customers->first()->note)
                        ->etc()
                )
        );
});

it('can view a customer', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    $response = $this->actingAs($user)->get('/customers/' . $customer->id);

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Customers/Show')
                ->has(
                    'customer',
                    fn (Assert $page) => $page
                        ->where('id', $customer->id)
                        ->where('name', $customer->name)
                        ->where('email', $customer->email)
                        ->where('phone', $customer->phone)
                        ->where('address', $customer->address)
                        ->where('note', $customer->note)
                        ->etc()
                )
        );
});
