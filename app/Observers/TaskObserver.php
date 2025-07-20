<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        $task->load('ticket.invoice');
        $task->ticket->fillTaskCounts()->save();

        // Update invoice subtotal if invoice exists
        $task->ticket->invoice?->fillTaskTotal()->save();
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        // Update counts if status changed
        if ($task->wasChanged(['status'])) {
            $task->load('ticket');
            $task->ticket->fillTaskCounts()->save();
        }

        // Update invoice subtotal if cost or billable status changed
        if ($task->wasChanged(['cost', 'is_billable'])) {
            $task->load('ticket.invoice');
            $task->ticket->invoice?->fillTaskTotal()->save();
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        $task->load('ticket.invoice');
        $task->ticket->fillTaskCounts()->save();

        // Update invoice subtotal if invoice exists
        $task->ticket->invoice?->fillTaskTotal()->save();
    }
}
