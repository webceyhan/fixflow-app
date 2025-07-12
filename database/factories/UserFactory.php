<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Database\Factories\States\HasContactableStates;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    use HasContactableStates {
        mailable as private;
        notMailable as private;
    }

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->e164PhoneNumber(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => UserRole::Technician,
            'status' => UserStatus::Active,
            'email_verified_at' => now(),
        ];
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the user has the specified role.
     */
    public function ofRole(UserRole $role): self
    {
        return $this->state(fn (array $attributes) => [
            'role' => $role,
        ]);
    }

    /**
     * Indicate that the user is an administrator.
     */
    public function admin(): self
    {
        return $this->ofRole(UserRole::Admin);
    }

    /**
     * Indicate that the user is a manager.
     */
    public function manager(): self
    {
        return $this->ofRole(UserRole::Manager);
    }

    /**
     * Indicate that the user has the specified status.
     */
    public function ofStatus(UserStatus $status): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }

    /**
     * Indicate that the user's email address is unverified.
     */
    public function unverified(): self
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
