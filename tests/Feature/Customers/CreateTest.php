<?php

use App\Enums\CustomerStatus;
use App\Enums\CustomerType;
use App\Models\Customer;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the create customer form', function () {
    // Arrange
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->get('/customers/create');

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->component('customers/Create'));
});

it('validates required fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/customers', []);

    // Assert
    $response->assertSessionHasErrors(['name']);
});

it('validates email format', function () {
    // Arrange
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/customers', [
        'name' => 'Test Customer',
        'email' => 'invalid-email',
        'type' => CustomerType::Individual->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['email']);
});

it('validates unique email', function () {
    // Arrange
    $existingCustomer = Customer::factory()->create(['email' => 'existing@example.com']);
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/customers', [
        'name' => 'Test Customer',
        'email' => 'existing@example.com',
        'type' => CustomerType::Individual->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['email']);
});

it('validates unique phone', function () {
    // Arrange
    $existingCustomer = Customer::factory()->create(['phone' => '+1234567890']);
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/customers', [
        'name' => 'Test Customer',
        'phone' => '+1234567890',
        'type' => CustomerType::Individual->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['phone']);
});

it('validates unique company', function () {
    // Arrange
    $existingCustomer = Customer::factory()->business('Existing Corp')->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/customers', [
        'name' => 'Test Customer',
        'company' => 'Existing Corp',
        'tax_number' => '123456789',
        'type' => CustomerType::Business->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['company']);
});

it('validates unique tax number', function () {
    // Arrange
    $existingCustomer = Customer::factory()->business(null, '123456789')->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/customers', [
        'name' => 'Test Customer',
        'company' => 'Test Corp',
        'tax_number' => '123456789',
        'type' => CustomerType::Business->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['tax_number']);
});

it('requires company and tax number for business customers', function () {
    // Arrange
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/customers', [
        'name' => 'Test Customer',
        'type' => CustomerType::Business->value,
        'status' => CustomerStatus::Active->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['company', 'tax_number']);
});

it('does not require company and tax number for individual customers', function () {
    // Arrange
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/customers', [
        'name' => 'Test Customer',
        'email' => 'test@example.com',
        'type' => CustomerType::Individual->value,
        'status' => CustomerStatus::Active->value,
    ]);

    // Assert
    $response->assertRedirect();
    expect(Customer::where('email', 'test@example.com')->first())->not->toBeNull();
});

it('creates an individual customer successfully', function () {
    // Arrange
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/customers', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'address' => '123 Main St',
        'note' => 'Test note',
        'type' => CustomerType::Individual->value,
        'status' => CustomerStatus::Active->value,
    ]);

    // Assert
    $response->assertRedirect();

    $customer = Customer::where('email', 'john@example.com')->first();
    expect($customer)->not->toBeNull();
    expect($customer->name)->toBe('John Doe');
    expect($customer->phone)->toBe('+1234567890');
    expect($customer->type)->toBe(CustomerType::Individual);
    expect($customer->status)->toBe(CustomerStatus::Active);
    expect($customer->company)->toBeNull();
    expect($customer->tax_number)->toBeNull();
});

it('creates a business customer successfully', function () {
    // Arrange
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/customers', [
        'name' => 'Jane Smith',
        'company' => 'Acme Corp',
        'tax_number' => '987654321',
        'email' => 'jane@acme.com',
        'phone' => '+0987654321',
        'type' => CustomerType::Business->value,
        'status' => CustomerStatus::Active->value,
    ]);

    // Assert
    $response->assertRedirect();

    $customer = Customer::where('email', 'jane@acme.com')->first();
    expect($customer)->not->toBeNull();
    expect($customer->name)->toBe('Jane Smith');
    expect($customer->company)->toBe('Acme Corp');
    expect($customer->tax_number)->toBe('987654321');
    expect($customer->type)->toBe(CustomerType::Business);
});

it('clears company and tax number when creating individual customer', function () {
    // Arrange
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/customers', [
        'name' => 'Test Customer',
        'company' => 'Should Be Ignored',
        'tax_number' => '123456789',
        'type' => CustomerType::Individual->value,
        'status' => CustomerStatus::Active->value,
    ]);

    // Assert
    $response->assertRedirect();

    $customer = Customer::where('name', 'Test Customer')->first();
    expect($customer->type)->toBe(CustomerType::Individual);
    expect($customer->company)->toBeNull();
    expect($customer->tax_number)->toBeNull();
});
