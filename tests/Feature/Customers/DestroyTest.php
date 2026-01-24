<?php

use App\Models\Customer;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('deletes a customer successfully', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->create();

    // Act
    $response = actingAs($manager)->delete("/customers/{$customer->id}");

    // Assert
    $response->assertRedirect('/customers');
    expect(Customer::find($customer->id))->toBeNull();
});
