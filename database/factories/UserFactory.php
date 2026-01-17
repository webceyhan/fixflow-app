<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Database\Factories\States\ActiveStatusStates;
use Database\Factories\States\NoteStates;
use Database\Factories\States\StatusStates;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    use ActiveStatusStates,
        NoteStates,
        StatusStates;

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
            'phone' => fake()->unique()->phoneNumber(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'note' => null,
            'role' => UserRole::Technician,
            'status' => UserStatus::Active,
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => null,
        ];
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the user has two-factor authentication configured.
     */
    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-1'])),
            'two_factor_confirmed_at' => now(),
        ]);
    }

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
     * Indicate that the user is a technician.
     */
    public function technician(): self
    {
        return $this->ofRole(UserRole::Technician);
    }

    /**
     * Indicate that the user is active.
     */
    public function active(): self
    {
        return $this->ofStatus(UserStatus::Active);
    }

    /**
     * Indicate that the user is suspended.
     */
    public function suspended(): self
    {
        return $this->ofStatus(UserStatus::Suspended);
    }

    /**
     * Indicate that the user is terminated.
     */
    public function terminated(): self
    {
        return $this->ofStatus(UserStatus::Terminated);
    }

    /**
     * Indicate that the user's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
