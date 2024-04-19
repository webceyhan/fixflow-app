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
        $task->ticket->fillTotalCost()->fillTaskCounters()->save();
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        if ($task->wasChanged(['cost', 'status'])) {
            $task->ticket->fillTotalCost()->fillTaskCounters()->save();
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        $task->ticket->fillTotalCost()->fillTaskCounters()->save();
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "saving" event.
     */
    public function saving(Task $task): void
    {
        if ($task->isCancelled()) {
            $task->is_billable = false;
        }
    }
}
