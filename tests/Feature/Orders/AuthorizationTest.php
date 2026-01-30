<?php

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

dataset('permissionsForOrders', [ // role, write access
    'Admin' => [UserRole::Admin, true],
    'Manager' => [UserRole::Manager, true],
    'Technician' => [UserRole::Technician, true],
]);

it('handles viewing orders list correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();

    // Act & Assert
    actingAs($user)->get('/orders')->assertSuccessful();
})->with('permissionsForOrders');

it('handles viewing order details correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $order = Order::factory()->create();

    // Act & Assert
    actingAs($user)->get("/orders/{$order->id}")->assertSuccessful();
})->with('permissionsForOrders');

it('handles order creation correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $ticket = Ticket::factory()->create();

    // Act & Assert
    actingAs($user)->get("/tickets/{$ticket->id}/orders/create")->assertSuccessful();
})->with('permissionsForOrders');

it('handles order editing correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $order = Order::factory()->create();

    // Act & Assert
    actingAs($user)->get("/orders/{$order->id}/edit")->assertSuccessful();
})->with('permissionsForOrders');

it('handles order deletion correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $order = Order::factory()->create();

    // Act & Assert
    actingAs($user)->delete("/orders/{$order->id}")->assertRedirect('/orders');
})->with('permissionsForOrders');
