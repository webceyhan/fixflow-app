<?php

namespace Database\Factories;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'model' => fake()->word(),
            'brand' => fake()->company(),
            'serial_number' => fake()->uuid(),
            'type' => DeviceType::Other,
            'status' => DeviceStatus::CheckedIn,
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the device belongs to the given customer.
     */
    public function forCustomer(Customer $customer): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => $customer->id,
        ]);
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the device has no brand.
     */
    public function withoutBrand(): self
    {
        return $this->state(fn (array $attributes) => [
            'brand' => null
        ]);
    }

    /**
     * Indicate that the device has no serial number.
     */
    public function withoutSerialNumber(): self
    {
        return $this->state(fn (array $attributes) => [
            'serial_number' => null
        ]);
    }

    /**
     * Indicate that the device has warranty.
     */
    public function withWarranty(int $years = 1): static
    {
        return $this->state(fn (array $attributes) => [
            'warranty_expire_date' => now()->addYear($years),
        ]);
    }

    /**
     * Indicate that the device is of the given type.
     */
    public function ofType(DeviceType $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }

    /**
     * Indicate that the device is of the given status.
     */
    public function ofStatus(DeviceStatus $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }
}
