<?php

use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the edit task form', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $task = Task::factory()->forTicket($ticket)->create();

    // Act
    $response = actingAs($manager)->get(route('tasks.edit', $task));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tasks/Edit')
        ->has('task')
        ->where('task.id', $task->id)
    );
});

it('validates required fields on update', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $task = Task::factory()->create();

    // Act
    $response = actingAs($manager)->put(route('tasks.update', $task), [
        'type' => null,
    ]);

    // Assert
    $response->assertSessionHasErrors(['type', 'priority', 'cost']);
});

it('does not allow changing ticket_id', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $task = Task::factory()->create();
    $originalTicketId = $task->ticket_id;

    // Act
    $response = actingAs($manager)->put(route('tasks.update', $task), [
        'type' => TaskType::Repair->value,
        'priority' => Priority::High->value,
        'cost' => 200,
        'is_billable' => true,
        'status' => TaskStatus::Completed->value,
    ]);

    // Assert
    $response->assertRedirect();
    expect($task->fresh()->ticket_id)->toBe($originalTicketId);
});

it('validates assignee exists on update', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $task = Task::factory()->create();

    // Act
    $response = actingAs($manager)->put(route('tasks.update', $task), [
        'type' => TaskType::Repair->value,
        'priority' => Priority::High->value,
        'cost' => 100,
        'is_billable' => true,
        'assignee_id' => 9999,
        'status' => TaskStatus::Created->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['assignee_id']);
});

it('updates a task successfully with all fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $assignee = User::factory()->create();
    $ticket = Ticket::factory()->create();
    $task = Task::factory()->forTicket($ticket)->create([
        'type' => TaskType::Diagnosis,
        'priority' => Priority::Low,
        'cost' => 50,
        'status' => TaskStatus::Created,
    ]);
    $originalTicketId = $task->ticket_id;

    // Act
    $response = actingAs($manager)->put(route('tasks.update', $task), [
        'type' => TaskType::Repair->value,
        'priority' => Priority::High->value,
        'cost' => 200.75,
        'is_billable' => true,
        'assignee_id' => $assignee->id,
        'note' => 'Updated task note',
        'status' => TaskStatus::Completed->value,
        'approved_at' => now()->format('Y-m-d H:i:s'),
    ]);

    // Assert
    $response->assertRedirect(route('tasks.show', $task));
    expect($task->fresh())
        ->ticket_id->toBe($originalTicketId)
        ->type->toBe(TaskType::Repair)
        ->priority->toBe(Priority::High)
        ->cost->toBe(200.75)
        ->is_billable->toBeTrue()
        ->assignee_id->toBe($assignee->id)
        ->note->toBe('Updated task note')
        ->status->toBe(TaskStatus::Completed)
        ->approved_at->not->toBeNull();
});

it('updates task and clears optional fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $assignee = User::factory()->create();
    $task = Task::factory()->create([
        'assignee_id' => $assignee->id,
        'note' => 'Original note',
        'approved_at' => now(),
    ]);

    // Act
    $response = actingAs($manager)->put(route('tasks.update', $task), [
        'type' => TaskType::Testing->value,
        'priority' => Priority::Medium->value,
        'cost' => 0,
        'is_billable' => false,
        'assignee_id' => null,
        'note' => null,
        'status' => TaskStatus::Created->value,
        'approved_at' => null,
    ]);

    // Assert
    $response->assertRedirect();
    expect($task->fresh())
        ->assignee_id->toBeNull()
        ->note->toBeNull()
        ->approved_at->toBeNull();
});
