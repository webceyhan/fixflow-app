<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Device::all()->each(function (Device $device) {
            Ticket::factory()->forDevice($device)->create();
        });

        $users = User::all();

        Device::all()->random(10)->each(function (Device $device) use ($users) {
            Ticket::factory()->forDevice($device)->forAssignee($users->random())->create();
        });
    }
}
