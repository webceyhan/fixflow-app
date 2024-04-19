<?php

use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Task;
use App\Models\Ticket;
use Illuminate\Support\Carbon;

it('can initialize task', function () {
    $task = new Task();

    expect($task->id)->toBeNull();
    expect($task->ticket_id)->toBeNull();
    expect($task->description)->toBeNull();
    expect($task->cost)->toBe(0.0);
    expect($task->is_billable)->toBeTrue();
    expect($task->type)->toBe(TaskType::Repair);
    expect($task->status)->toBe(TaskStatus::New);
    expect($task->created_at)->toBeNull();
    expect($task->updated_at)->toBeNull();
});

it('can create task', function () {
    $task = Task::factory()->create();

    expect($task->id)->toBeInt();
    expect($task->ticket_id)->toBeInt();
    expect($task->description)->toBeString();
    expect($task->cost)->toBeFloat();
    expect($task->is_billable)->toBeTrue();
    expect($task->type)->toBe(TaskType::Repair);
    expect($task->status)->toBe(TaskStatus::New);
    expect($task->created_at)->toBeInstanceOf(Carbon::class);
    expect($task->updated_at)->toBeInstanceOf(Carbon::class);
});

it('can create task as free', function () {
    $task = Task::factory()->free()->create();

    expect($task->is_billable)->toBeFalse();
});

it('can create task of type', function (TaskType $type) {
    $task = Task::factory()->ofType($type)->create();

    expect($task->type)->toBe($type);
})->with(TaskType::cases());

it('can create task of status', function (TaskStatus $status) {
    $task = Task::factory()->ofStatus($status)->create();

    expect($task->status)->toBe($status);
})->with(TaskStatus::cases());

it('can update task', function () {
    $task = Task::factory()->create();

    $task->update([
        'description' => 'Replace the battery',
        'cost' => 100,
        'is_billable' => false,
        'type' => TaskType::Maintenance,
        'status' => TaskStatus::Completed,
    ]);

    expect($task->description)->toBe('Replace the battery');
    expect($task->cost)->toBe(100.0);
    expect($task->is_billable)->toBeFalse();
    expect($task->type)->toBe(TaskType::Maintenance);
    expect($task->status)->toBe(TaskStatus::Completed);
});

it('can delete task', function () {
    $task = Task::factory()->create();

    $task->delete();

    expect(Task::find($task->id))->toBeNull();
});

// Ticket //////////////////////////////////////////////////////////////////////////////////////////

it('belongs to a ticket', function () {
    $ticket = Ticket::factory()->create();
    $task = Task::factory()->forTicket($ticket)->create();

    expect($task->ticket)->toBeInstanceOf(Ticket::class);
    expect($task->ticket->id)->toBe($ticket->id);
});

// Billable ////////////////////////////////////////////////////////////////////////////////////////

it('can filter tasks by billable scope', function () {
    Task::factory()->create();
    Task::factory()->free()->create();

    expect(Task::billable()->count())->toBe(1);
    expect(Task::billable()->first()->is_billable)->toBeTrue();
});

// Type ////////////////////////////////////////////////////////////////////////////////////////////

it('can filter tasks by type scope', function (TaskType $type) {
    Task::factory()->ofType($type)->create();

    expect(Task::ofType($type)->count())->toBe(1);
    expect(Task::ofType($type)->first()->type)->toBe($type);
})->with(TaskType::cases());

// Status //////////////////////////////////////////////////////////////////////////////////////////

it('can filter tasks by status scope', function (TaskStatus $status) {
    Task::factory()->ofStatus($status)->create();

    expect(Task::ofStatus($status)->count())->toBe(1);
    expect(Task::ofStatus($status)->first()->status)->toBe($status);
})->with(TaskStatus::cases());
