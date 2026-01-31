<?php

use App\Enums\UserRole;
use App\Models\Invoice;
use App\Models\User;

use function Pest\Laravel\actingAs;

dataset('permissionsForInvoices', [ // role, write access
    'Admin' => [UserRole::Admin, true],
    'Manager' => [UserRole::Manager, true],
    'Technician' => [UserRole::Technician, true],
]);

it('handles viewing invoices list correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();

    // Act & Assert
    actingAs($user)->get('/invoices')->assertSuccessful();
})->with('permissionsForInvoices');

it('handles viewing invoice details correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $invoice = Invoice::factory()->create();

    // Act & Assert
    actingAs($user)->get("/invoices/{$invoice->id}")->assertSuccessful();
})->with('permissionsForInvoices');

it('handles invoice editing correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $invoice = Invoice::factory()->create();

    // Act & Assert
    actingAs($user)->get("/invoices/{$invoice->id}/edit")->assertSuccessful();
})->with('permissionsForInvoices');

it('prevents invoice creation - no create route exists', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $ticket = \App\Models\Ticket::factory()->create();

    // Act & Assert
    // Invoices are auto-created with tickets, so no create route exists
    actingAs($user)->get("/tickets/{$ticket->id}/invoices/create")->assertNotFound();
})->with('permissionsForInvoices');

it('prevents invoice deletion - invoices are permanent', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $invoice = Invoice::factory()->create();

    // Act & Assert
    actingAs($user)->delete("/invoices/{$invoice->id}")->assertMethodNotAllowed();
})->with('permissionsForInvoices');
