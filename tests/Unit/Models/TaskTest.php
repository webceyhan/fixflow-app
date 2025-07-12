<?php

use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Task;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

it('creates a task with valid attributes', function () {
    // Arrange & Act
    $task = Task::factory()->create();

    // Assert
    expect($task->ticket_id)->not->toBeNull();
    expect($task->description)->not->toBeEmpty();
    expect($task->cost)->toBeGreaterThan(0);
    expect($task->is_billable)->toBeTrue();
    expect($task->status)->toBeInstanceOf(TaskStatus::class);
    expect($task->type)->toBeInstanceOf(TaskType::class);
    expect($task->approved_at)->toBeInstanceOf(Carbon::class);
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

it('can update a task', function () {
    // Arrange
    $task = Task::factory()->create();

    // Act
    $task->update([
        'description' => 'Updated task description',
        'cost' => 200,
        'is_billable' => false,
        'type' => TaskType::Inspection,
        'status' => TaskStatus::Completed,
        'approved_at' => now(),
    ]);

    // Assert
    expect($task->description)->toBe('Updated task description');
    expect($task->cost)->toEqual(200);
    expect($task->is_billable)->toBeFalse();
    expect($task->type)->toBe(TaskType::Inspection);
    expect($task->status)->toBe(TaskStatus::Completed);
    expect($task->approved_at)->toBeInstanceOf(Carbon::class);
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

it('can filter tasks by type scope', function (TaskType $type) {
    // Arrange
    Task::factory()->ofType($type)->create();

    // Act
    $tasks = Task::query()->ofType($type)->get();

    // Assert
    expect($tasks)->toHaveCount(1);
    expect($tasks->first()->type)->toBe($type);
})->with(TaskType::cases());
