<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ticket::all()->each(function (Ticket $ticket) {
            // Create a random number of tasks for each ticket.
            $amount = rand(1, 2);

            Task::factory($amount)->forTicket($ticket)->create();
        });
    }
}
