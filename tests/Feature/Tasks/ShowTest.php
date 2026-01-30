<?php

use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the task show page', function () {
    // Arrange
    $viewer = User::factory()->create();
    $ticket = Ticket::factory()->create();
    $task = Task::factory()->forTicket($ticket)->create();

    // Act
    $response = actingAs($viewer)->get(route('tasks.show', $task));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tasks/Show')
        ->has('task')
        ->where('task.id', $task->id)
        ->where('task.type', $task->type->value)
    );
});

it('loads task with ticket relationship', function () {
    // Arrange
    $viewer = User::factory()->create();
    $ticket = Ticket::factory()->create();
    $task = Task::factory()->forTicket($ticket)->create();

    // Act
    $response = actingAs($viewer)->get(route('tasks.show', $task));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('task.ticket')
        ->where('task.ticket.id', $ticket->id)
    );
});

it('loads task with assignee relationship', function () {
    // Arrange
    $viewer = User::factory()->create();
    $assignee = User::factory()->create();
    $ticket = Ticket::factory()->create();
    $task = Task::factory()->forTicket($ticket)->create(['assignee_id' => $assignee->id]);

    // Act
    $response = actingAs($viewer)->get(route('tasks.show', $task));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('task.assignee')
        ->where('task.assignee.id', $assignee->id)
    );
});
