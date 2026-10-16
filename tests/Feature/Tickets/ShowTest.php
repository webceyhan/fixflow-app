<?php

use App\Models\Attachment;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the ticket show page', function () {
    // Arrange
    $viewer = User::factory()->create();
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();
    $ticket = Ticket::factory()->forDevice($device)->create();

    // Act
    $response = actingAs($viewer)->get(route('tickets.show', $ticket));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tickets/Show')
        ->has('ticket')
        ->where('ticket.id', $ticket->id)
        ->where('ticket.title', $ticket->title)
    );
});

it('loads ticket with device and customer relationships', function () {
    // Arrange
    $viewer = User::factory()->create();
    $customer = Customer::factory()->create();
    $device = Device::factory()->forCustomer($customer)->create();
    $ticket = Ticket::factory()->forDevice($device)->create();

    // Act
    $response = actingAs($viewer)->get(route('tickets.show', $ticket));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('ticket.device')
        ->has('ticket.customer')
        ->where('ticket.device.id', $device->id)
        ->where('ticket.customer.id', $customer->id)
    );
});

it('loads ticket with assignee relationship', function () {
    // Arrange
    $viewer = User::factory()->create();
    $assignee = User::factory()->create();
    $device = Device::factory()->create();
    $ticket = Ticket::factory()->forDevice($device)->forAssignee($assignee)->create();

    // Act
    $response = actingAs($viewer)->get(route('tickets.show', $ticket));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('ticket.assignee')
        ->where('ticket.assignee.id', $assignee->id)
    );
});

it('loads ticket attachments', function () {
    // Arrange
    $viewer = User::factory()->create();
    $ticket = Ticket::factory()->create();
    $attachment = Attachment::factory()->forTicket($ticket)->create();

    // Act
    $response = actingAs($viewer)->get(route('tickets.show', $ticket));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('ticket.attachments', 1)
        ->where('ticket.attachments.0.id', $attachment->id)
        ->where('ticket.attachments.0.name', $attachment->name)
    );
});
