<?php

use App\Enums\Priority;
use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the create ticket form', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();

    // Act
    $response = actingAs($manager)->get("/devices/{$device->id}/tickets/create");

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->component('tickets/Create'));
});

it('validates required fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $device = Device::factory()->create();

    // Act
    $response = actingAs($manager)->post("/devices/{$device->id}/tickets", []);

    // Assert
    $response->assertSessionHasErrors(['title']);
});

it('returns 404 when device does not exist', function () {
    // Arrange
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/devices/9999/tickets', [
        'title' => 'Test Ticket',
    ]);

    // Assert
    $response->assertNotFound();
});

it('validates assignee exists', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $device = Device::factory()->create();

    // Act
    $response = actingAs($manager)->post("/devices/{$device->id}/tickets", [
        'title' => 'Test Ticket',
        'assignee_id' => 9999,
    ]);

    // Assert
    $response->assertSessionHasErrors(['assignee_id']);
});

it('validates due date is not in the past', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $device = Device::factory()->create();

    // Act
    $response = actingAs($manager)->post("/devices/{$device->id}/tickets", [
        'title' => 'Test Ticket',
        'due_date' => now()->subDay()->format('Y-m-d'),
    ]);

    // Assert
    $response->assertSessionHasErrors(['due_date']);
});

it('creates a ticket successfully with all fields', function () {
    // Arrange
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();
    $assignee = User::factory()->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/devices/{$device->id}/tickets", [
        'assignee_id' => $assignee->id,
        'title' => 'Screen Repair',
        'note' => 'Customer reports cracked screen',
        'priority' => Priority::High->value,
        'status' => TicketStatus::Created->value,
        'due_date' => now()->addDays(3)->format('Y-m-d'),
    ]);

    // Assert
    $response->assertRedirect();

    $ticket = Ticket::where('title', 'Screen Repair')->first();
    expect($ticket)->not->toBeNull();
    expect($ticket->device_id)->toBe($device->id);
    expect($ticket->assignee_id)->toBe($assignee->id);
    expect($ticket->priority)->toBe(Priority::High);
    expect($ticket->status)->toBe(TicketStatus::Created);
});

it('creates a ticket with minimal required fields', function () {
    // Arrange
    $device = Device::factory()->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/devices/{$device->id}/tickets", [
        'title' => 'Screen Repair',
        'priority' => Priority::Medium->value,
        'status' => TicketStatus::Created->value,
    ]);

    // Assert
    $response->assertRedirect();

    $ticket = Ticket::where('title', 'Screen Repair')->first();
    expect($ticket)->not->toBeNull();
    expect($ticket->device_id)->toBe($device->id);
    expect($ticket->assignee_id)->toBeNull();
    expect($ticket->note)->toBeNull();
});

it('redirects to ticket show page after creation', function () {
    // Arrange
    $device = Device::factory()->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/devices/{$device->id}/tickets", [
        'title' => 'Test Ticket',
        'priority' => Priority::Medium->value,
        'status' => TicketStatus::Created->value,
    ]);

    // Assert
    $ticket = Ticket::where('title', 'Test Ticket')->first();
    $response->assertRedirect(route('tickets.show', $ticket));
});
