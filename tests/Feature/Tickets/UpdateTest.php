<?php

use App\Enums\Priority;
use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the edit ticket form', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();
    $ticket = Ticket::factory()->forDevice($device)->create();

    // Act
    $response = actingAs($manager)->get(route('tickets.edit', $ticket));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tickets/Edit')
        ->has('ticket')
        ->where('ticket.id', $ticket->id)
    );
});

it('validates required fields on update', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();

    // Act
    $response = actingAs($manager)->put(route('tickets.update', $ticket), [
        'title' => null,
    ]);

    // Assert
    $response->assertSessionHasErrors(['title']);
});

it('does not allow changing device_id', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $originalDeviceId = $ticket->device_id;

    // Act
    $response = actingAs($manager)->put(route('tickets.update', $ticket), [
        'title' => 'Updated Title',
        'priority' => Priority::High->value,
        'status' => TicketStatus::InProgress->value,
    ]);

    // Assert
    $response->assertRedirect();
    expect($ticket->fresh()->device_id)->toBe($originalDeviceId);
});

it('validates assignee exists on update', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();

    // Act
    $response = actingAs($manager)->put(route('tickets.update', $ticket), [
        'title' => 'Updated Title',
        'assignee_id' => 9999,
        'priority' => Priority::High->value,
        'status' => TicketStatus::InProgress->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['assignee_id']);
});

it('updates a ticket successfully with all fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $assignee = User::factory()->create();
    $device = Device::factory()->create();
    $ticket = Ticket::factory()->forDevice($device)->create([
        'title' => 'Original Title',
        'priority' => Priority::Low,
        'status' => TicketStatus::Created,
    ]);
    $originalDeviceId = $ticket->device_id;

    // Act
    $response = actingAs($manager)->put(route('tickets.update', $ticket), [
        'assignee_id' => $assignee->id,
        'title' => 'Updated Screen Repair',
        'note' => 'Updated note',
        'priority' => Priority::High->value,
        'status' => TicketStatus::InProgress->value,
        'due_date' => now()->addWeek()->format('Y-m-d'),
    ]);

    // Assert
    $response->assertRedirect(route('tickets.show', $ticket));
    expect($ticket->fresh())
        ->device_id->toBe($originalDeviceId)
        ->assignee_id->toBe($assignee->id)
        ->title->toBe('Updated Screen Repair')
        ->note->toBe('Updated note')
        ->priority->toBe(Priority::High)
        ->status->toBe(TicketStatus::InProgress);
});

it('updates ticket and clears optional fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $assignee = User::factory()->create();
    $ticket = Ticket::factory()->create([
        'assignee_id' => $assignee->id,
        'note' => 'Original note',
        'due_date' => now()->addWeek(),
    ]);

    // Act
    $response = actingAs($manager)->put(route('tickets.update', $ticket), [
        'device_id' => $ticket->device_id,
        'title' => $ticket->title,
        'assignee_id' => null,
        'note' => null,
        'priority' => $ticket->priority->value,
        'status' => $ticket->status->value,
        'due_date' => null,
    ]);

    // Assert
    $response->assertRedirect();
    expect($ticket->fresh())
        ->assignee_id->toBeNull()
        ->note->toBeNull()
        ->due_date->toBeNull();
});
