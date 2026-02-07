<?php

namespace Database\Factories;

use App\Enums\CustomerStatus;
use App\Enums\CustomerType;
use Database\Factories\States\ActiveStatusStates;
use Database\Factories\States\NoteStates;
use Database\Factories\States\StatusStates;
use Database\Factories\States\TypeStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    use ActiveStatusStates,
        NoteStates,
        StatusStates,
        TypeStates;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'company' => null,
            'tax_number' => null,
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->phoneNumber(),
            'address' => fake()->optional()->address(),
            'note' => null,
            'type' => CustomerType::Individual,
            'status' => CustomerStatus::Active,
        ];
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the customer is an individual (no company).
     */
    public function individual(): self
    {
        return $this
            ->ofType(CustomerType::Individual)
            ->state(fn (array $attributes) => [
                'company' => null,
                'tax_number' => null,
            ]);
    }

    /**
     * Indicate that the customer is a business (with company).
     */
    public function business(?string $company = null, ?string $taxNumber = null): self
    {
        return $this
            ->ofType(CustomerType::Business)
            ->state(fn (array $attributes) => [
                'company' => $company ?? fake()->unique()->company(),
                'tax_number' => $taxNumber ?? fake()->unique()->ean13(),
            ]);
    }

    /**
     * Indicate that the customer is active.
     */
    public function active(): self
    {
        return $this->ofStatus(CustomerStatus::Active);
    }

    /**
     * Indicate that the customer is suspended.
     */
    public function suspended(): self
    {
        return $this->ofStatus(CustomerStatus::Suspended);
    }

    /**
     * Indicate that the customer is archived.
     */
    public function archived(): self
    {
        return $this->ofStatus(CustomerStatus::Archived);
    }
}
