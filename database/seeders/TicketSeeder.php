<?php

namespace Database\Seeders;

use App\Models\Customer;
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
        Customer::all()->each(function (Customer $customer) {
            Ticket::factory()->forCustomer($customer)->create();
        });

        $users = User::all();

        Customer::all()->random(10)->each(function (Customer $customer) use ($users) {
            Ticket::factory()->forCustomer($customer)->forAssignee($users->random())->create();
        });
    }
}
