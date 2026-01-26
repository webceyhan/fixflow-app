<?php

namespace Database\Seeders;

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
        // Get all devices
        $devices = Device::all();

        // Create tickets for all devices
        $devices->each(function (Device $device) {
            Ticket::factory()->forDevice($device)->create();
        });

        // Create tickets for random devices assigned to every active user.
        // This ensures that each device has tickets assigned to different users
        User::active()->each(function (User $user) use ($devices) {
            // Ensure users have at least one ticket assigned
            $devices->random(5)->each(fn (Device $device) => Ticket::factory()
                ->forDevice($device)
                ->forAssignee($user)
                ->create());
        });
    }
}
