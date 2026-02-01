<?php

use App\Enums\UserRole;
use App\Models\Adjustment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

dataset('permissionsForAdjustments', [
    UserRole::Admin,
    UserRole::Manager,
    UserRole::Technician,
]);

it('handles viewing adjustments list correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();

    // Act & Assert
    actingAs($user)->get('/adjustments')->assertOk();
})->with('permissionsForAdjustments');

it('handles viewing adjustment details correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $adjustment = Adjustment::factory()->create();

    // Act & Assert
    actingAs($user)->get("/adjustments/{$adjustment->id}")->assertOk();
})->with('permissionsForAdjustments');

it('handles adjustment creation correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $adjustment = Adjustment::factory()->make();

    // Act & Assert
    actingAs($user)->get("/invoices/{$adjustment->invoice_id}/adjustments/create")->assertOk();
})->with('permissionsForAdjustments');

it('handles adjustment editing correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $adjustment = Adjustment::factory()->create();

    // Act & Assert
    actingAs($user)->get("/adjustments/{$adjustment->id}/edit")->assertOk();
})->with('permissionsForAdjustments');

it('handles adjustment deletion correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $adjustment = Adjustment::factory()->create();

    // Act & Assert
    actingAs($user)->delete("/adjustments/{$adjustment->id}")->assertRedirect(route('invoices.show', $adjustment->invoice_id));
})->with('permissionsForAdjustments');
