<?php

use App\Enums\CustomerStatus;
use App\Enums\CustomerType;
use App\Models\Customer;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the edit customer form', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->create();

    // Act
    $response = actingAs($manager)->get("/customers/{$customer->id}/edit");

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('customers/Edit')
        ->has('customer')
    );
});

it('validates required fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->create();

    // Act
    $response = actingAs($manager)->put("/customers/{$customer->id}", [
        'name' => '',
    ]);

    // Assert
    $response->assertSessionHasErrors(['name']);
});

it('validates email format', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->create();

    // Act
    $response = actingAs($manager)->put("/customers/{$customer->id}", [
        'name' => 'Test Customer',
        'email' => 'invalid-email',
        'type' => $customer->type->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['email']);
});

it('validates unique email except for current customer', function () {
    // Arrange
    $existingCustomer = Customer::factory()->create(['email' => 'existing@example.com']);
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->create(['email' => 'current@example.com']);

    // Act
    $response = actingAs($manager)->put("/customers/{$customer->id}", [
        'name' => 'Test Customer',
        'email' => 'existing@example.com',
        'type' => $customer->type->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['email']);
});

it('validates unique phone except for current customer', function () {
    // Arrange
    $existingCustomer = Customer::factory()->create(['phone' => '+1234567890']);
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->create();

    // Act
    $response = actingAs($manager)->put("/customers/{$customer->id}", [
        'name' => 'Test Customer',
        'phone' => '+1234567890',
        'type' => $customer->type->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['phone']);
});

it('allows keeping the same email', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->create(['email' => 'keep@example.com']);

    // Act
    $response = actingAs($manager)->put("/customers/{$customer->id}", [
        'name' => 'Updated Name',
        'email' => 'keep@example.com',
        'type' => $customer->type->value,
    ]);

    // Assert
    $response->assertRedirect();
    expect($customer->fresh()->email)->toBe('keep@example.com');
});

it('validates unique company except for current customer', function () {
    // Arrange
    $existingCustomer = Customer::factory()->business('Existing Corp')->create();
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->business('Current Corp')->create();

    // Act
    $response = actingAs($manager)->put("/customers/{$customer->id}", [
        'name' => 'Test Customer',
        'company' => 'Existing Corp',
        'tax_number' => '123456789',
        'type' => CustomerType::Business->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['company']);
});

it('validates unique tax number except for current customer', function () {
    // Arrange
    $existingCustomer = Customer::factory()->business(null, '123456789')->create();
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->business(null, '987654321')->create();

    // Act
    $response = actingAs($manager)->put("/customers/{$customer->id}", [
        'name' => 'Test Customer',
        'company' => 'Test Corp',
        'tax_number' => '123456789',
        'type' => CustomerType::Business->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['tax_number']);
});

it('requires company and tax number when updating to business type', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->individual()->create();

    // Act
    $response = actingAs($manager)->put("/customers/{$customer->id}", [
        'name' => 'Test Customer',
        'type' => CustomerType::Business->value,
        'status' => $customer->status->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['company', 'tax_number']);
});

it('updates an individual customer successfully', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->individual()->create();

    // Act
    $response = actingAs($manager)->put("/customers/{$customer->id}", [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'phone' => '+9999999999',
        'address' => 'New Address',
        'note' => 'Updated note',
        'type' => CustomerType::Individual->value,
        'status' => CustomerStatus::Active->value,
    ]);

    // Assert
    $response->assertRedirect();

    $customer->refresh();
    expect($customer->name)->toBe('Updated Name');
    expect($customer->email)->toBe('updated@example.com');
    expect($customer->phone)->toBe('+9999999999');
    expect($customer->type)->toBe(CustomerType::Individual);
    expect($customer->company)->toBeNull();
    expect($customer->tax_number)->toBeNull();
});

it('updates a business customer successfully', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->business()->create();

    // Act
    $response = actingAs($manager)->put("/customers/{$customer->id}", [
        'name' => 'Updated Name',
        'company' => 'Updated Corp',
        'tax_number' => 'NEW123456',
        'email' => 'updated@company.com',
        'type' => CustomerType::Business->value,
        'status' => CustomerStatus::Active->value,
    ]);

    // Assert
    $response->assertRedirect();

    $customer->refresh();
    expect($customer->name)->toBe('Updated Name');
    expect($customer->company)->toBe('Updated Corp');
    expect($customer->tax_number)->toBe('NEW123456');
    expect($customer->type)->toBe(CustomerType::Business);
});

it('clears company and tax number when changing from business to individual', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->business('Old Corp', '123456')->create();

    // Act
    $response = actingAs($manager)->put("/customers/{$customer->id}", [
        'name' => 'Updated Name',
        'type' => CustomerType::Individual->value,
        'status' => CustomerStatus::Active->value,
    ]);

    // Assert
    $response->assertRedirect();

    $customer->refresh();
    expect($customer->type)->toBe(CustomerType::Individual);
    expect($customer->company)->toBeNull();
    expect($customer->tax_number)->toBeNull();
});

it('sets company and tax number when changing from individual to business', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->individual()->create();

    // Act
    $response = actingAs($manager)->put("/customers/{$customer->id}", [
        'name' => 'Updated Name',
        'company' => 'New Corp',
        'tax_number' => 'NEW789',
        'type' => CustomerType::Business->value,
        'status' => CustomerStatus::Active->value,
    ]);

    // Assert
    $response->assertRedirect();

    $customer->refresh();
    expect($customer->type)->toBe(CustomerType::Business);
    expect($customer->company)->toBe('New Corp');
    expect($customer->tax_number)->toBe('NEW789');
});
