<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'company' => $this->faker->company(),
            'vat_number' => $this->faker->unique()->ean13(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'address' => $this->faker->address(),
            'note' => $this->faker->optional()->sentence(),
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
     * Indicate that the customer has no email.
     */
    public function withoutEmail(): self
    {
        return $this->state(fn (array $attributes) => [
            'email' => null,
        ]);
    }

    /**
     * Indicate that the customer has no phone number.
     */
    public function withoutPhone(): self
    {
        return $this->state(fn (array $attributes) => [
            'phone' => null,
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
