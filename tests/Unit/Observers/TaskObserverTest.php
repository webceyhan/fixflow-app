<?php

use App\Models\Invoice;
use App\Models\Task;
use App\Models\Ticket;
use App\Observers\TaskObserver;

beforeEach(function () {
    $this->observer = new TaskObserver;

    // Helpers

    $this->mockTask = function (bool $syncTicket = false, bool $syncInvoice = false) {
        $task = mock(Task::class);
        $ticket = mock(Ticket::class);

        if ($syncTicket) {
            $ticket->shouldReceive('fillTaskCounts')->once()->andReturnSelf();
            $ticket->shouldReceive('save')->once()->andReturn(true);
        }

        if ($syncInvoice) {
            $invoice = mock(Invoice::class);
            $invoice->shouldReceive('fillTaskTotal')->once()->andReturnSelf();
            $invoice->shouldReceive('save')->once()->andReturn(true);

            $ticket->shouldReceive('getAttribute')->with('invoice')->andReturn($invoice);
        }

        $task->shouldReceive('load')->with('ticket')->andReturnSelf();
        $task->shouldReceive('load')->with('ticket.invoice')->andReturnSelf();
        $task->shouldReceive('getAttribute')->with('ticket')->andReturn($ticket);

        return $task;
    };

    $this->mockTaskWithUpdates = function (bool $statusChanged = false, bool $costOrBillableChanged = false) {
        $task = $this->mockTask(syncTicket: $statusChanged, syncInvoice: $costOrBillableChanged);

        $task->shouldReceive('wasChanged')
            ->once()
            ->with(['status'])
            ->andReturn($statusChanged);

        $task->shouldReceive('wasChanged')
            ->once()
            ->with(['cost', 'is_billable'])
            ->andReturn($costOrBillableChanged);

        return $task;
    };
});

it('updates ticket task-counts and invoice task-total on creation', function () {
    // Arrange
    $task = $this->mockTask(syncTicket: true, syncInvoice: true);

    // Act
    $this->observer->created($task);
});

it('does nothing when no relevant fields were changed', function () {
    // Arrange
    $task = $this->mockTaskWithUpdates();

    // Act
    $this->observer->updated($task);
});

it('updates ticket task-counts when status was changed', function () {
    // Arrange
    $task = $this->mockTaskWithUpdates(statusChanged: true);

    $this->observer->updated($task);
});

it('updates invoice task-total when cost or billable was changed', function () {
    // Arrange
    $task = $this->mockTaskWithUpdates(costOrBillableChanged: true);

    $this->observer->updated($task);
});

it('updates ticket task-counts and invoice task-total when both status and cost or billable was changed', function () {
    // Arrange
    $task = $this->mockTaskWithUpdates(statusChanged: true, costOrBillableChanged: true);

    $this->observer->updated($task);
});

it('updates ticket task-counts and invoice task-total on deletion', function () {
    // Arrange
    $task = $this->mockTask(syncTicket: true, syncInvoice: true);

    // Act
    $this->observer->deleted($task);
});
