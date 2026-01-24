<?php

use App\Models\Customer;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('allows authenticated users to view a customer profile', function () {
    // Arrange
    $viewer = User::factory()->create();
    $customer = Customer::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    // Act
    $response = actingAs($viewer)->get(route('customers.show', $customer));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('customers/Show')
        ->has('customer')
        ->where('customer.id', $customer->id)
        ->where('customer.name', 'John Doe')
        ->where('customer.email', 'john@example.com')
    );
});

it('shows business customer details with company information', function () {
    // Arrange
    $viewer = User::factory()->create();
    $customer = Customer::factory()->business('Acme Corp', '123456789')->create();

    // Act
    $response = actingAs($viewer)->get(route('customers.show', $customer));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('customers/Show')
        ->where('customer.company', 'Acme Corp')
        ->where('customer.tax_number', '123456789')
    );
});
