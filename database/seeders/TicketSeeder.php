<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\Device;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create tickets for all devices
        Device::all()->each(function (Device $device) {
            Ticket::factory()->forDevice($device)->create();
        });

        // Get all active users
        $users = User::ofStatus(UserStatus::Active)->get();

        // Create tickets for random devices assigned to random users
        // This ensures that each device has tickets assigned to different users
        Device::all()->random(10)->each(function (Device $device) use ($users) {
            Ticket::factory()->forDevice($device)->forAssignee($users->random())->create();
        });
    }
}
