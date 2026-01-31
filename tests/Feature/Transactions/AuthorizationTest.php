<?php

use App\Enums\UserRole;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\User;

use function Pest\Laravel\actingAs;

dataset('permissionsForTransactions', [ // role, write access
    'Admin' => [UserRole::Admin, true],
    'Manager' => [UserRole::Manager, true],
    'Technician' => [UserRole::Technician, true],
]);

it('handles viewing transactions list correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();

    // Act & Assert
    actingAs($user)->get('/transactions')->assertSuccessful();
})->with('permissionsForTransactions');

it('handles viewing transaction details correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $transaction = Transaction::factory()->create();

    // Act & Assert
    actingAs($user)->get("/transactions/{$transaction->id}")->assertSuccessful();
})->with('permissionsForTransactions');

it('handles transaction creation correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $invoice = Invoice::factory()->create();

    // Act & Assert
    actingAs($user)->get("/invoices/{$invoice->id}/transactions/create")->assertSuccessful();
})->with('permissionsForTransactions');

it('handles transaction editing correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $transaction = Transaction::factory()->create();

    // Act & Assert
    actingAs($user)->get("/transactions/{$transaction->id}/edit")->assertSuccessful();
})->with('permissionsForTransactions');

it('handles transaction deletion correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $transaction = Transaction::factory()->create();

    // Act & Assert
    actingAs($user)->delete("/transactions/{$transaction->id}")->assertRedirect(route('invoices.show', $transaction->invoice_id));
})->with('permissionsForTransactions');
