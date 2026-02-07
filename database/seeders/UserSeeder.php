<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Database\Fixtures\UserFixture;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default demo users (one per role)
        User::factory()
            ->admin()
            ->state(UserFixture::emailByRole())
            ->create([
                'created_at' => now()->subMonths(6),
            ]);

        User::factory()
            ->manager()
            ->state(UserFixture::emailByRole())
            ->create([
                'created_at' => now()->subMonths(5),
            ]);

        User::factory()
            ->technician()
            ->state(UserFixture::emailByRole())
            ->create([
                'created_at' => now()->subMonths(4),
            ]);

        // Create additional users across all role/status combinations
        UserRole::all()->each(function (UserRole $role) {
            UserStatus::all()->each(function (UserStatus $status) use ($role) {
                User::factory()
                    ->ofRole($role)
                    ->ofStatus($status)
                    ->create([
                        'created_at' => now()->subMonths(rand(1, 3)),
                    ]);
            });
        });

        // Create users with combined states for testing purposes
        User::factory(5)
            ->unverified()
            ->state(UserFixture::noteByStatus())
            ->create();

        User::factory(5)
            ->suspended()
            ->state(UserFixture::noteByStatus())
            ->create();

        User::factory(5)
            ->terminated()
            ->withTwoFactor()
            ->state(UserFixture::noteByStatus())
            ->create();
    }
}
