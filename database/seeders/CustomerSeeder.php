<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Database\Fixtures\CustomerFixture;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function (User $user) {
            // Get random start date relative to user
            $startDate = fn () => fake()->dateTimeBetween($user->created_at);

            // Create sample active customers
            Customer::factory(5)
                ->active()
                ->state(CustomerFixture::noteByStatus())
                ->create([
                    'created_at' => $startDate(),
                ]);

            // Create some suspended customers
            Customer::factory(2)
                ->suspended()
                ->state(CustomerFixture::noteByStatus())
                ->create([
                    'created_at' => $startDate(),
                ]);

            // Create some archived customers
            Customer::factory(1)
                ->archived()
                ->state(CustomerFixture::noteByStatus())
                ->create([
                    'created_at' => $startDate(),
                ]);

            // Create some customers as individual type
            Customer::factory(3)
                ->individual()
                ->state(CustomerFixture::noteByStatus())
                ->create([
                    'created_at' => $startDate(),
                ]);

            // Create some customers as business type
            Customer::factory(2)
                ->business()
                ->state(CustomerFixture::noteByStatus())
                ->create([
                    'created_at' => $startDate(),
                ]);
        });
    }
}
