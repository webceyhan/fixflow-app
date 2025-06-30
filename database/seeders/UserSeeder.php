<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default admin user (earliest)
        User::factory()->admin()->create([
            'email' => 'admin@demo.com',
            'created_at' => now()->subMonths(6),
        ]);

        // Create a default manager user (after admin)
        User::factory()->manager()->create([
            'email' => 'manager@demo.com',
            'created_at' => now()->subMonths(5),
        ]);

        // Create a default technician user (after manager)
        User::factory()->create([
            'email' => 'technician@demo.com',
            'created_at' => now()->subMonths(4),
        ]);

        // Create additional random users for each status (recently)
        UserStatus::all()->each(function (UserStatus $status) {
            User::factory(5)->ofStatus($status)->create([
                'created_at' => now()->subMonths(rand(1, 3)),
            ]);
        });
    }
}
