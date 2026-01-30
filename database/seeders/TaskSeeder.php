<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
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

        // Create 1-3 tasks for each ticket
        Ticket::all()->each(function (Ticket $ticket) {
            Task::factory()
                ->count(fake()->numberBetween(1, 3))
                ->forTicket($ticket)
                ->create();
        });

        // Create some completed tasks
        Ticket::all()->random(15)->each(function (Ticket $ticket) use ($users) {
            Task::factory()
                ->forTicket($ticket)
                ->forAssignee($users->random())
                ->completed()
                ->create();
        });

        // Create some cancelled tasks
        Ticket::all()->random(5)->each(function (Ticket $ticket) {
            Task::factory()
                ->forTicket($ticket)
                ->cancelled()
                ->create();
        });

        // Create some pre-approved tasks
        Ticket::all()->random(10)->each(function (Ticket $ticket) {
            Task::factory()
                ->forTicket($ticket)
                ->approved()
                ->create();
        });
    }
}
