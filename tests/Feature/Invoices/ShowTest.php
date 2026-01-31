<?php

use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the invoice show page', function () {
    // Arrange
    $viewer = User::factory()->create();
    $ticket = Ticket::factory()->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    // Act
    $response = actingAs($viewer)->get(route('invoices.show', $invoice));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('invoices/Show')
        ->has('invoice')
        ->where('invoice.id', $invoice->id)
        ->where('invoice.status', $invoice->status->value)
    );
});

it('loads invoice with ticket relationship', function () {
    // Arrange
    $viewer = User::factory()->create();
    $ticket = Ticket::factory()->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    // Act
    $response = actingAs($viewer)->get(route('invoices.show', $invoice));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('invoice.ticket')
        ->where('invoice.ticket.id', $ticket->id)
    );
});

it('loads invoice with customer through ticket', function () {
    // Arrange
    $viewer = User::factory()->create();
    $customer = \App\Models\Customer::factory()->create();
    $device = \App\Models\Device::factory()->forCustomer($customer)->create();
    $ticket = Ticket::factory()->forDevice($device)->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    // Act
    $response = actingAs($viewer)->get(route('invoices.show', $invoice));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('invoice.ticket.customer')
        ->where('invoice.ticket.customer.id', $customer->id)
    );
});

it('loads invoice with device through ticket', function () {
    // Arrange
    $viewer = User::factory()->create();
    $device = \App\Models\Device::factory()->create();
    $ticket = Ticket::factory()->forDevice($device)->create();
    $invoice = Invoice::factory()->forTicket($ticket)->create();

    // Act
    $response = actingAs($viewer)->get(route('invoices.show', $invoice));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('invoice.ticket.device')
        ->where('invoice.ticket.device.id', $device->id)
    );
});
