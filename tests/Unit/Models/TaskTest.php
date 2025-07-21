<?php

use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Concerns\Billable;
use App\Models\Concerns\HasApproval;
use App\Models\Concerns\HasProgress;
use App\Models\Concerns\HasStatus;
use App\Models\Concerns\HasType;
use App\Models\Task;
use App\Models\Ticket;
use App\Observers\TaskObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Task::class,
    concerns: [
        Billable::class,
        HasApproval::class,
        HasProgress::class,
        HasStatus::class,
        HasType::class,
    ],
    observers: [
        TaskObserver::class,
    ],
    defaults: [
        'type' => TaskType::Repair,
        'status' => TaskStatus::New,
    ],
    fillables: [
        'cost',
        'is_billable',
        'note',
        'type',
        'status',
        'approved_at',
    ],
    casts: [
        'cost' => 'float',
    ]
);

// RELATION TESTS //////////////////////////////////////////////////////////////////////////////////

it('belongs to ticket relationship', function () {
    // Arrange
    $ticket = Ticket::factory()->create();
    $task = Task::factory()->forTicket($ticket)->create();

    // Assert
    expect($task->ticket)->toBeInstanceOf(Ticket::class);
    expect($task->ticket->id)->toBe($ticket->id);
});
