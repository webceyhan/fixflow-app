<?php

use App\Models\Invoice;
use App\Models\Task;
use App\Models\Ticket;
use App\Observers\TaskObserver;

beforeEach(function () {
    $this->observer = new TaskObserver;
});

it('updates ticket task-counts and invoice task total on creation', function () {
    // Arrange
    $task = mock(Task::class);
    $ticket = mock(Ticket::class);
    $invoice = mock(Invoice::class);

    $task->shouldReceive('load')
        ->once()
        ->with('ticket.invoice')
        ->andReturnSelf();

    // The observer accesses $task->ticket twice
    $task->shouldReceive('getAttribute')
        ->with('ticket')
        ->twice()
        ->andReturn($ticket);

    $ticket->shouldReceive('fillTaskCounts')
        ->once()
        ->andReturnSelf();

    $ticket->shouldReceive('save')
        ->once()
        ->andReturn(true);

    // Mock invoice relationship access
    $ticket->shouldReceive('getAttribute')
        ->with('invoice')
        ->once()
        ->andReturn($invoice);

    $invoice->shouldReceive('fillTaskTotal')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('save')
        ->once()
        ->andReturn(true);

    // Act
    $this->observer->created($task);
});

it('does nothing when no relevant fields were changed', function () {
    // Arrange
    $task = mock(Task::class);

    $task->shouldReceive('wasChanged')
        ->once()
        ->with(['status'])
        ->andReturn(false);

    $task->shouldReceive('wasChanged')
        ->once()
        ->with(['cost', 'is_billable'])
        ->andReturn(false);

    // ticket should not be loaded or modified when no relevant changes
    $task->shouldNotReceive('load');

    // Act
    $this->observer->updated($task);
});

it('updates ticket task-counts when status was changed', function () {
    // Arrange
    $task = mock(Task::class);
    $ticket = mock(Ticket::class);

    $task->shouldReceive('wasChanged')
        ->once()
        ->with(['status'])
        ->andReturn(true);

    $task->shouldReceive('wasChanged')
        ->once()
        ->with(['cost', 'is_billable'])
        ->andReturn(false);

    $task->shouldReceive('load')
        ->once()
        ->with('ticket')
        ->andReturnSelf();

    $task->shouldReceive('getAttribute')
        ->with('ticket')
        ->once()
        ->andReturn($ticket);

    $ticket->shouldReceive('fillTaskCounts')
        ->once()
        ->andReturnSelf();

    $ticket->shouldReceive('save')
        ->once()
        ->andReturn(true);

    $this->observer->updated($task);
});

it('updates invoice task total when cost or billable status changed', function () {
    // Arrange
    $task = mock(Task::class);
    $ticket = mock(Ticket::class);
    $invoice = mock(Invoice::class);

    $task->shouldReceive('wasChanged')
        ->once()
        ->with(['status'])
        ->andReturn(false);

    $task->shouldReceive('wasChanged')
        ->once()
        ->with(['cost', 'is_billable'])
        ->andReturn(true);

    $task->shouldReceive('load')
        ->once()
        ->with('ticket.invoice')
        ->andReturnSelf();

    $task->shouldReceive('getAttribute')
        ->with('ticket')
        ->once()
        ->andReturn($ticket);

    $ticket->shouldReceive('getAttribute')
        ->with('invoice')
        ->once()
        ->andReturn($invoice);

    $invoice->shouldReceive('fillTaskTotal')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('save')
        ->once()
        ->andReturn(true);

    $this->observer->updated($task);
});

it('updates both ticket counts and invoice total when both status and cost changed', function () {
    // Arrange
    $task = mock(Task::class);
    $ticket = mock(Ticket::class);
    $invoice = mock(Invoice::class);

    $task->shouldReceive('wasChanged')
        ->once()
        ->with(['status'])
        ->andReturn(true);

    $task->shouldReceive('wasChanged')
        ->once()
        ->with(['cost', 'is_billable'])
        ->andReturn(true);

    // First load for status change
    $task->shouldReceive('load')
        ->once()
        ->with('ticket')
        ->andReturnSelf();

    // Second load for cost/billable change
    $task->shouldReceive('load')
        ->once()
        ->with('ticket.invoice')
        ->andReturnSelf();

    $task->shouldReceive('getAttribute')
        ->with('ticket')
        ->twice()
        ->andReturn($ticket);

    $ticket->shouldReceive('fillTaskCounts')
        ->once()
        ->andReturnSelf();

    $ticket->shouldReceive('save')
        ->once()
        ->andReturn(true);

    $ticket->shouldReceive('getAttribute')
        ->with('invoice')
        ->once()
        ->andReturn($invoice);

    $invoice->shouldReceive('fillTaskTotal')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('save')
        ->once()
        ->andReturn(true);

    $this->observer->updated($task);
});

it('updates ticket task-counts and invoice task total on deletion', function () {
    // Arrange
    $task = mock(Task::class);
    $ticket = mock(Ticket::class);
    $invoice = mock(Invoice::class);

    $task->shouldReceive('load')
        ->once()
        ->with('ticket.invoice')
        ->andReturnSelf();

    // The observer accesses $task->ticket twice
    $task->shouldReceive('getAttribute')
        ->with('ticket')
        ->twice()
        ->andReturn($ticket);

    $ticket->shouldReceive('fillTaskCounts')
        ->once()
        ->andReturnSelf();

    $ticket->shouldReceive('save')
        ->once()
        ->andReturn(true);

    // Mock invoice relationship access
    $ticket->shouldReceive('getAttribute')
        ->with('invoice')
        ->once()
        ->andReturn($invoice);

    $invoice->shouldReceive('fillTaskTotal')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('save')
        ->once()
        ->andReturn(true);

    // Act
    $this->observer->deleted($task);
});
