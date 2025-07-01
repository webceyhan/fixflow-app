<?php

use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Task;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a task with valid attributes', function () {
    // Arrange & Act
    $task = Task::factory()->create();

    // Assert
    expect($task->ticket_id)->not->toBeNull();
    expect($task->cost)->toBeGreaterThan(0);
    expect($task->note)->not->toBeEmpty();
    expect($task->status)->toBeInstanceOf(TaskStatus::class);
    expect($task->type)->toBeInstanceOf(TaskType::class);
});

it('can create a task for a ticket', function () {
    // Arrange
    $ticket = Ticket::factory()->create();

    // Act
    $task = Task::factory()->forTicket($ticket)->create();

    // Assert
    expect($task->ticket->id)->toBe($ticket->id);
});

it('can create a task of type', function (TaskType $type) {
    // Arrange & Act
    $task = Task::factory()->ofType($type)->create();

    // Assert
    expect($task->type)->toBe($type);
})->with(TaskType::cases());

it('can create a task of status', function (TaskStatus $status) {
    // Arrange & Act
    $task = Task::factory()->ofStatus($status)->create();

    // Assert
    expect($task->status)->toBe($status);
})->with(TaskStatus::cases());

it('can update a task', function () {
    // Arrange
    $task = Task::factory()->create();

    // Act
    $task->update([
        'cost' => 200,
        'is_billable' => false,
        'note' => 'Updated task note',
        'type' => TaskType::Diagnostic,
        'status' => TaskStatus::Completed,
    ]);

    // Assert
    expect($task->cost)->toEqual(200);
    expect($task->is_billable)->toBeFalse();
    expect($task->note)->toBe('Updated task note');
    expect($task->type)->toBe(TaskType::Diagnostic);
    expect($task->status)->toBe(TaskStatus::Completed);
});

it('can delete a task', function () {
    // Arrange
    $task = Task::factory()->create();

    // Act
    $task->delete();

    // Assert
    expect(Task::find($task->id))->toBeNull();
});

it('belongs to a ticket', function () {
    $ticket = Ticket::factory()->create();
    $task = Task::factory()->forTicket($ticket)->create();

    expect($task->ticket)->toBeInstanceOf(Ticket::class);
    expect($task->ticket->id)->toBe($ticket->id);
});

it('can determine if a task is billable', function () {
    // Arrange & Act & Assert
    $task = Task::factory()->make(['is_billable' => true]);
    expect($task->is_billable)->toBeTrue();

    $task = Task::factory()->make(['is_billable' => false]);
    expect($task->is_billable)->toBeFalse();
});

it('can filter tasks by billable scope', function () {
    // Arrange
    Task::factory()->create();
    Task::factory()->nonBillable()->create();

    // Act
    $tasks = Task::query()->billable()->get();

    // Assert
    expect($tasks)->toHaveCount(1);
    expect($tasks->first()->is_billable)->toBeTrue();
});

it('can filter tasks by type scope', function (TaskType $type) {
    // Arrange
    Task::factory()->ofType($type)->create();

    // Act
    $tasks = Task::query()->ofType($type)->get();

    // Assert
    expect($tasks)->toHaveCount(1);
    expect($tasks->first()->type)->toBe($type);
})->with(TaskType::cases());

it('can filter tasks by status scope', function (TaskStatus $status) {
    // Arrange
    Task::factory()->ofStatus($status)->create();

    // Act
    $tasks = Task::query()->ofStatus($status)->get();

    // Assert
    expect($tasks)->tohavecount(1);
    expect($tasks->first()->status)->toBe($status);
})->with(TaskStatus::cases());

it('can filter tasks by approved scope', function () {
    // Arrange
    Task::factory()->create();

    // Act
    $tasks = Task::query()->approved()->get();

    // Assert
    expect($tasks)->toHaveCount(1);
    expect($tasks->first()->approved_at)->not->toBeNull();
});
