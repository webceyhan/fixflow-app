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
