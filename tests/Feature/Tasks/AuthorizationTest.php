<?php

use App\Enums\UserRole;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

dataset('permissionsForTasks', [ // role, write access
    'Admin' => [UserRole::Admin, true],
    'Manager' => [UserRole::Manager, true],
    'Technician' => [UserRole::Technician, true],
]);

it('handles viewing tasks list correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();

    // Act & Assert
    actingAs($user)->get('/tasks')->assertSuccessful();
})->with('permissionsForTasks');

it('handles viewing task details correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $task = Task::factory()->create();

    // Act & Assert
    actingAs($user)->get("/tasks/{$task->id}")->assertSuccessful();
})->with('permissionsForTasks');

it('handles task creation correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $ticket = Ticket::factory()->create();

    // Act & Assert
    actingAs($user)->get("/tickets/{$ticket->id}/tasks/create")->assertSuccessful();
})->with('permissionsForTasks');

it('handles task editing correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $task = Task::factory()->create();

    // Act & Assert
    actingAs($user)->get("/tasks/{$task->id}/edit")->assertSuccessful();
})->with('permissionsForTasks');

it('handles task deletion correctly', function (UserRole $role) {
    // Arrange
    $user = User::factory()->ofRole($role)->create();
    $task = Task::factory()->create();

    // Act & Assert
    actingAs($user)->delete("/tasks/{$task->id}")->assertRedirect('/tasks');
})->with('permissionsForTasks');
