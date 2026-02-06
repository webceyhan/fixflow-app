<?php

use App\Models\Customer;
use App\Models\Device;
use App\Models\Ticket;
use App\Support\QueryBuilder\Filters\SearchFilter;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->customer1 = Customer::factory()->create(['name' => 'John Doe']);
    $this->customer2 = Customer::factory()->create(['name' => 'Jane Smith']);
    $this->customer3 = Customer::factory()->create(['name' => 'Bob Johnson']);
});

it('searches single column', function () {
    // Arrange
    $filter = new SearchFilter(['name']);
    $query = Customer::query();

    // Act
    $filter($query, 'John', 'search');
    $results = $query->get();

    // Assert
    expect($results)->toHaveCount(2)
        ->and($results->pluck('id'))->toContain($this->customer1->id, $this->customer3->id);
});

it('searches multiple columns', function () {
    // Arrange
    $this->customer1->update(['email' => 'john@example.com']);
    $this->customer2->update(['email' => 'jane@smith.com']);
    $filter = new SearchFilter(['name', 'email']);
    $query = Customer::query();

    // Act
    $filter($query, 'smith', 'search');
    $results = $query->get();

    // Assert
    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($this->customer2->id);
});

it('searches relationships', function () {
    // Arrange
    $customer = Customer::factory()->create(['name' => 'Test Customer']);
    $device = Device::factory()->for($customer)->create();
    $ticket1 = Ticket::factory()->for($device)->create();
    $ticket2 = Ticket::factory()->create();
    $filter = new SearchFilter(['device.customer.name']);
    $query = Ticket::query();

    // Act
    $filter($query, 'Test Customer', 'search');
    $results = $query->get();

    // Assert
    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($ticket1->id);
});

it('is case insensitive', function () {
    // Arrange
    $filter = new SearchFilter(['name']);
    $query = Customer::query();

    // Act
    $filter($query, 'JOHN', 'search');
    $results = $query->get();

    // Assert
    expect($results)->toHaveCount(2);
});

it('returns no results when search term does not match', function () {
    // Arrange
    $filter = new SearchFilter(['name']);
    $query = Customer::query();

    // Act
    $filter($query, 'NonExistent', 'search');

    // Assert
    expect($query->get())->toHaveCount(0);
});

it('handles empty search value', function () {
    // Arrange
    $filter = new SearchFilter(['name']);
    $query = Customer::query();

    // Act
    $filter($query, '', 'search');

    // Assert
    expect($query->get())->toHaveCount(3);
});
