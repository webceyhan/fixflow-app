<?php

use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the create task form', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();

    // Act
    $response = actingAs($manager)->get("/tickets/{$ticket->id}/tasks/create");

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->component('tasks/Create'));
});

it('validates required fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();

    // Act
    $response = actingAs($manager)->post("/tickets/{$ticket->id}/tasks", []);

    // Assert
    $response->assertSessionHasErrors(['cost', 'is_billable']);
});

it('returns 404 when ticket does not exist', function () {
    // Arrange
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post('/tickets/9999/tasks', [
        'type' => TaskType::Repair->value,
        'priority' => Priority::Medium->value,
        'cost' => 100,
        'is_billable' => true,
    ]);

    // Assert
    $response->assertNotFound();
});

it('validates assignee exists', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();

    // Act
    $response = actingAs($manager)->post("/tickets/{$ticket->id}/tasks", [
        'type' => TaskType::Repair->value,
        'priority' => Priority::Medium->value,
        'cost' => 100,
        'is_billable' => true,
        'assignee_id' => 9999,
    ]);

    // Assert
    $response->assertSessionHasErrors(['assignee_id']);
});

it('validates cost is numeric and minimum 0', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();

    // Act
    $response = actingAs($manager)->post("/tickets/{$ticket->id}/tasks", [
        'type' => TaskType::Repair->value,
        'priority' => Priority::Medium->value,
        'cost' => -10,
        'is_billable' => true,
    ]);

    // Assert
    $response->assertSessionHasErrors(['cost']);
});

it('creates a task successfully with all fields', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    $assignee = User::factory()->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/tickets/{$ticket->id}/tasks", [
        'type' => TaskType::Repair->value,
        'priority' => Priority::High->value,
        'cost' => 150.50,
        'is_billable' => true,
        'assignee_id' => $assignee->id,
        'note' => 'Replace damaged screen',
        'status' => TaskStatus::Created->value,
        'approved_at' => now()->format('Y-m-d H:i:s'),
    ]);

    // Assert
    $response->assertRedirect();
    expect(Task::count())->toBe(1);

    $task = Task::first();
    expect($task)
        ->ticket_id->toBe($ticket->id)
        ->type->toBe(TaskType::Repair)
        ->priority->toBe(Priority::High)
        ->cost->toBe(150.50)
        ->is_billable->toBeTrue()
        ->assignee_id->toBe($assignee->id)
        ->note->toBe('Replace damaged screen')
        ->status->toBe(TaskStatus::Created)
        ->approved_at->not->toBeNull();
});

it('creates a task with minimal required fields', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    $manager = User::factory()->manager()->create();

    // Act
    $response = actingAs($manager)->post("/tickets/{$ticket->id}/tasks", [
        'type' => TaskType::Diagnosis->value,
        'priority' => Priority::Low->value,
        'status' => TaskStatus::Created->value,
        'cost' => 0,
        'is_billable' => false,
    ]);

    // Assert
    $response->assertRedirect();
    expect(Task::count())->toBe(1);

    $task = Task::first();
    expect($task)
        ->ticket_id->toBe($ticket->id)
        ->type->toBe(TaskType::Diagnosis)
        ->priority->toBe(Priority::Low)
        ->cost->toBe(0.0)
        ->is_billable->toBeFalse()
        ->assignee_id->toBeNull()
        ->note->toBeNull()
        ->approved_at->toBeNull();
});
