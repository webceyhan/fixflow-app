<?php

namespace Database\Seeders;

use App\Enums\TicketStatus;
use App\Models\Device;
use App\Models\Ticket;
use App\Models\User;
use Database\Fixtures\TicketFixture;
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
            Ticket::factory()
                ->forDevice($device)
                ->state(TicketFixture::issueByDevice($device))
                ->create();
        });

        // Create 5-10 assigned tickets per active user across random devices
        User::active()->each(function (User $user) use ($devices) {
            $devices->random(rand(5, 10))->each(function (Device $device) use ($user) {
                Ticket::factory()
                    ->forDevice($device)
                    ->forAssignee($user)
                    ->ofStatus(TicketStatus::all()->random())
                    ->state(TicketFixture::issueByDevice($device))
                    ->create();
            });
        });
    }
}
