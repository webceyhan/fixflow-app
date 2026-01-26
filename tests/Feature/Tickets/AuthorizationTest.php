<?php

use App\Enums\UserRole;
use App\Models\Device;
use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

dataset('permissionsForTickets', [ // role, write access
    'Admin' => [UserRole::Admin, true],
    'Manager' => [UserRole::Manager, true],
    'Technician' => [UserRole::Technician, true],
]);

it('handles viewing tickets list correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();

    // Act & Assert
    actingAs($user)->get('/tickets')->assertSuccessful();
})->with('permissionsForTickets');

it('handles viewing ticket details correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $ticket = Ticket::factory()->create();

    // Act & Assert
    actingAs($user)->get("/tickets/{$ticket->id}")->assertSuccessful();
})->with('permissionsForTickets');

it('handles ticket creation correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $device = Device::factory()->create();

    // Act & Assert
    actingAs($user)->get("/devices/{$device->id}/tickets/create")->assertSuccessful();
})->with('permissionsForTickets');

it('handles ticket editing correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $ticket = Ticket::factory()->create();

    // Act & Assert
    actingAs($user)->get("/tickets/{$ticket->id}/edit")->assertSuccessful();
})->with('permissionsForTickets');

it('handles ticket deletion correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $ticket = Ticket::factory()->create();

    // Act & Assert
    actingAs($user)->delete("/tickets/{$ticket->id}")->assertRedirect('/tickets');
})->with('permissionsForTickets');
