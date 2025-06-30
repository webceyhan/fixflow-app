<?php

declare(strict_types=1);

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a customer with default attributes', function () {
    // Arrange & Act
    $customer = Customer::factory()->create();

    // Assert
    expect($customer->name)->not->toBeNull();
    expect($customer->email)->not->toBeNull();
    expect($customer->address)->not->toBeNull();
});

it('can create customer without email', function () {
    // Act
    $customer = Customer::factory()->withoutEmail()->create();

    // Assert
    expect($customer->email)->toBeNull();
});

it('can create customer without phone', function () {
    // Act
    $customer = Customer::factory()->withoutPhone()->create();

    // Assert
    expect($customer->phone)->toBeNull();
});

it('can create customer without address', function () {
    // Act
    $customer = Customer::factory()->withoutAddress()->create();

    // Assert
    expect($customer->address)->toBeNull();
});

it('can create customer without note', function () {
    // Act
    $customer = Customer::factory()->withoutNote()->create();

    // Assert
    expect($customer->note)->toBeNull();
});

it('can update customer', function () {
    // Arrange
    $customer = Customer::factory()->create();

    // Act
    $customer->update([
        'name' => 'Acme Corp',
        'email' => 'contact@acme.com',
        'phone' => '+1234567890',
        'address' => '123 Main St',
        'note' => 'VIP',
    ]);
    $customer = $customer->fresh();

    // Assert
    expect($customer->name)->toBe('Acme Corp');
    expect($customer->email)->toBe('contact@acme.com');
    expect($customer->phone)->toBe('+1234567890');
    expect($customer->address)->toBe('123 Main St');
    expect($customer->note)->toBe('VIP');
});

it('can delete customer', function () {
    // Arrange
    $customer = Customer::factory()->create();

    // Act
    $customer->delete();

    // Assert
    expect(Customer::find($customer->id))->toBeNull();
});
