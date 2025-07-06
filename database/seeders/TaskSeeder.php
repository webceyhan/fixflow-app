<?php

namespace Database\Seeders;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Create a random number of tasks for each ticket.
        Ticket::all()->each(function (Ticket $ticket) {

            // Create normal task
            Task::factory()->forTicket($ticket)->create();

            // Create a task that needs approval
            Task::factory()->forTicket($ticket)->unapproved()->create();
        });

        // Mark some tasks as non-billable
        Task::all()->random(5)->each(function (Task $task) {
            $task->is_billable = false;
            $task->save();
        });

        // Mark some tasks as cancelled
        Task::all()->random(10)->each(function (Task $task) {
            $task->status = TaskStatus::Cancelled;
            $task->save();
        });

        // Mark some tasks as completed
        Task::ofStatus(TaskStatus::New)->get()->random(20)->each(function (Task $task) {
            $task->status = TaskStatus::Completed;
            $task->save();
        });
    }
}
