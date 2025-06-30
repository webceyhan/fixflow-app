<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function (User $user) {
            // Create a customer for the user
            $startDate = fn () => fake()->dateTimeBetween($user->created_at);

            // Create a default customer
            Customer::factory()->create([
                'created_at' => $startDate,
            ]);

            // Create a customer without company
            Customer::factory()->withoutCompany()->create([
                'created_at' => $startDate,
            ]);

            // Create a customer without email
            Customer::factory()->withoutEmail()->create([
                'created_at' => $startDate,
            ]);

            // Create a customer without phone
            Customer::factory()->withoutPhone()->create([
                'created_at' => $startDate,
            ]);

            // Create a customer without address
            Customer::factory()->withoutAddress()->create([
                'created_at' => $startDate,
            ]);

            // Create a customer without note
            Customer::factory()->withoutNote()->create([
                'created_at' => $startDate,
            ]);
        });
    }
}
