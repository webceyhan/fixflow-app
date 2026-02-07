<?php

namespace Database\Seeders;

use App\Enums\TaskType;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use Database\Fixtures\TaskFixture;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all active users
        $users = User::active()->get();

        // Get all tickets
        $tickets = Ticket::all();

        // Create tasks with specific notes from fixtures
        $tickets->each(function (Ticket $ticket) {
            TaskType::all()
                ->random(rand(1, 3))
                ->each(function (TaskType $type) use ($ticket) {
                    Task::factory()
                        ->forTicket($ticket)
                        ->ofType($type)
                        ->state(TaskFixture::noteByType())
                        ->create();
                });
        });

        // Create some completed tasks
        $tickets->random(15)->each(function (Ticket $ticket) use ($users) {
            Task::factory()
                ->forTicket($ticket)
                ->forAssignee($users->random())
                ->completed()
                ->state(TaskFixture::noteByType())
                ->create();
        });

        // Create some pre-approved tasks
        $tickets->random(10)->each(function (Ticket $ticket) {
            Task::factory()
                ->forTicket($ticket)
                ->approved()
                ->state(TaskFixture::noteByType())
                ->create();
        });

        // Create some cancelled tasks
        $tickets->random(5)->each(function (Ticket $ticket) {
            Task::factory()
                ->forTicket($ticket)
                ->cancelled()
                ->create();
        });
    }
}
