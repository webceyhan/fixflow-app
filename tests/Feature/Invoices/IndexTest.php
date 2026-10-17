<?php

use App\Models\Customer;
use App\Models\Device;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the invoices list to authenticated users', function () {
    // Arrange
    $viewer = User::factory()->create();
    $invoices = Invoice::factory()->count(3)->create();

    // Act
    $response = actingAs($viewer)->get(route('invoices.index'));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('invoices/Index')
        ->has('invoices.data', 3)
        ->has('invoices.links')
        ->has('filter')
    );
});

it('allows searching invoices by customer name', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $viewer = User::factory()->create();

    // Create customer with unique name
    $customer = Customer::factory()->create(['name' => "{$uid} Tech Solutions"]);

    // Create device for that customer
    $device = Device::factory()->create(['customer_id' => $customer->id]);

    // Create tickets for that device
    $tickets = Ticket::factory()->count(2)->create(['device_id' => $device->id]);

    // Create invoices for those tickets
    foreach ($tickets as $ticket) {
        Invoice::factory()->create(['ticket_id' => $ticket->id]);
    }

    // Create one unrelated invoice
    Invoice::factory()->create();

    // Act
    $response = actingAs($viewer)->get(route('invoices.index', ['filter' => ['search' => $uid]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('invoices/Index')
        ->has('invoices.data', 2)
    );
});

it('allows filtering invoices by status', function () {
    // Arrange
    $viewer = User::factory()->create();
    Invoice::factory()->count(2)->create(['status' => 'draft']);
    Invoice::factory()->count(3)->create(['status' => 'paid']);

    // Act
    $response = actingAs($viewer)->get(route('invoices.index', ['filter' => ['status' => 'draft']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('invoices/Index')
        ->has('invoices.data', 2)
    );
});

it('allows filtering invoices by created_at', function () {
    // Arrange
    $viewer = User::factory()->create();
    Invoice::factory()->count(2)->create(['created_at' => now()->subDays(5)]);
    Invoice::factory()->count(3)->create(['created_at' => now()]);

    // Act
    $response = actingAs($viewer)->get(route('invoices.index', ['filter' => ['created_at' => 'last_week']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('invoices/Index')
        ->has('invoices.data', 5)
    );
});

it('sorts invoices by total', function () {
    // Arrange
    $viewer = User::factory()->create();
    $invoice1 = Invoice::factory()->create(['total' => 100]);
    $invoice2 = Invoice::factory()->create(['total' => 200]);
    $invoice3 = Invoice::factory()->create(['total' => 150]);

    // Act
    $response = actingAs($viewer)->get(route('invoices.index', ['sort' => 'total']));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('invoices/Index')
        ->where('invoices.data.0.id', $invoice1->id)
        ->where('invoices.data.1.id', $invoice3->id)
        ->where('invoices.data.2.id', $invoice2->id)
    );
});

it('sorts invoices by due_date', function () {
    // Arrange
    $viewer = User::factory()->create();
    $invoice1 = Invoice::factory()->create(['due_date' => now()->addDays(1)]);
    $invoice2 = Invoice::factory()->create(['due_date' => now()->addDays(10)]);
    $invoice3 = Invoice::factory()->create(['due_date' => now()->addDays(5)]);

    // Act
    $response = actingAs($viewer)->get(route('invoices.index', ['sort' => 'due_date']));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('invoices/Index')
        ->where('invoices.data.0.id', $invoice1->id)
        ->where('invoices.data.1.id', $invoice3->id)
        ->where('invoices.data.2.id', $invoice2->id)
    );
});
