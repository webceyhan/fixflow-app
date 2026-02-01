<?php

use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Concerns\Approvable;
use App\Models\Concerns\Assignable;
use App\Models\Concerns\Billable;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasCancelledStatus;
use App\Models\Concerns\HasPriority;
use App\Models\Concerns\HasStatus;
use App\Models\Concerns\HasType;
use App\Models\Task;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Task::class,
    concerns: [
        Approvable::class,
        Assignable::class,
        Billable::class,
        HasActiveStatus::class,
        HasCancelledStatus::class,
        HasPriority::class,
        HasStatus::class,
        HasType::class,
    ],
    defaults: [
        'cost' => 0,
        'is_billable' => true,
        'priority' => Priority::Medium,
        'type' => TaskType::Repair,
        'status' => TaskStatus::Created,
    ],
    fillables: [
        'ticket_id',
        'assignee_id',
        'cost',
        'is_billable',
        'note',
        'priority',
        'type',
        'status',
        'approved_at',
    ],
    hiddens: [
        // No hidden attributes
    ],
    casts: [
        'cost' => 'float',
        'is_billable' => 'boolean',
        'priority' => Priority::class,
        'type' => TaskType::class,
        'status' => TaskStatus::class,
        'approved_at' => 'datetime',
    ],
    relations: [
        'ticket' => BelongsTo::class,
        'assignee' => BelongsTo::class,
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

// SCOPE TESTS /////////////////////////////////////////////////////////////////////////////////////

// METHOD TESTS ////////////////////////////////////////////////////////////////////////////////////
