<?php

namespace Database\Factories;

use Database\Factories\States\HasContactableStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 *
 * @method static hasDevices(int $count = 1, array $attributes = [])
 */
class CustomerFactory extends Factory
{
    use HasContactableStates;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'company' => fake()->unique()->company(),
            'vat_number' => fake()->unique()->ean13(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->phoneNumber(),
            'address' => fake()->address(),
            'note' => fake()->optional()->sentence(),
        ];
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the customer has no company or VAT number.
     */
    public function withoutCompany(): self
    {
        return $this->state(fn (array $attributes) => [
            'company' => null,
            'vat_number' => null,
        ]);
    }

    /**
     * Indicate that the customer has no address.
     */
    public function withoutAddress(): self
    {
        return $this->state(fn (array $attributes) => [
            'address' => null,
        ]);
    }

    /**
     * Indicate that the customer has no note.
     */
    public function withoutNote(): self
    {
        return $this->state(fn (array $attributes) => [
            'note' => null,
        ]);
    }
}
