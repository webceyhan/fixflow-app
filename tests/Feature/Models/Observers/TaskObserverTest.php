<?php

use App\Models\Task;
use App\Models\Ticket;

it('can update ticket total_cost on all events', function () {
    $ticket = Ticket::factory()->create();
    $task = Task::factory()->forTicket($ticket)->create();

    // ignore non-billable tasks
    Task::factory()->forTicket($ticket)->free()->create();

    $ticket->refresh();

    expect($ticket->total_cost)->toBe($task->cost);

    // update task cost
    $task->update(['cost' => 100.0]);
    $ticket->refresh();

    expect($ticket->total_cost)->toBe(100.0);

    // delete task
    $task->delete();
    $ticket->refresh();

    expect($ticket->total_cost)->toBe(0.0);
});
