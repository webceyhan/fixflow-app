<?php

use App\Models\Customer;
use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a customer with valid attributes', function () {
    // Arrange & Act
    $customer = Customer::factory()->create();

    // Assert
    expect($customer->name)->not->toBeEmpty();
    expect($customer->email)->not->toBeEmpty();
    expect($customer->address)->not->toBeEmpty();
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

it('can have many devices', function () {
    // Arrange & Act
    $customer = Customer::factory()->hasDevices(2)->create();

    // Assert
    expect($customer->devices)->toHaveCount(2);
});

it('can have many tickets via devices', function () {
    $customer = Customer::factory()->create();
    Device::factory()->count(2)->forCustomer($customer)->hasTickets(2)->create();

    expect($customer->tickets)->toHaveCount(4);
});
