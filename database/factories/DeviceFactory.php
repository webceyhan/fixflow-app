<?php

namespace Database\Factories;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Models\Customer;
use Database\Factories\States\ActiveStatusStates;
use Database\Factories\States\NoteStates;
use Database\Factories\States\StatusStates;
use Database\Factories\States\TypeStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
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
        $type = fake()->randomElement(DeviceType::cases());
        $brand = fake()->company();
        $name = fake()->word();
        $version = fake()->randomElement(['Pro', 'Max', 'Ultra', 'Lite', 'Plus', fake()->randomDigit()]);
        $color = fake()->optional()->safeColorName();
        $imei = $type->hasCellular() ? fake()->optional()->numerify('###############') : null;

        return [
            'customer_id' => Customer::factory(),
            'model' => "{$name} {$version}",
            'brand' => $brand,
            'type' => $type,
            'color' => $color,
            'imei' => $imei,
            'serial_number' => fake()->optional()->regexify('[A-Z0-9]{10,15}'),
            'passcode' => fake()->optional()->numerify('####'),
            'purchase_date' => null,
            'warranty_expire_date' => null,
            'note' => null,
            'status' => DeviceStatus::Received,
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the device belongs to the given customer.
     */
    public function forCustomer(Customer $customer): self
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => $customer->id,
            'created_at' => fake()->dateTimeBetween($customer->created_at),
        ]);
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the device has warranty information.
     */
    public function withWarranty(bool $expired = false): self
    {
        $purchaseDate = fake()->dateTimeBetween('-2 years', '-1 month');
        $warrantyExpireDate = fake()->dateTimeBetween($purchaseDate, $expired ? '-1 day' : '+2 years');

        return $this->state(fn (array $attributes) => [
            'purchase_date' => $purchaseDate,
            'warranty_expire_date' => $warrantyExpireDate,
        ]);
    }

    /**
     * Indicate that the device has been received.
     */
    public function received(): self
    {
        return $this->ofStatus(DeviceStatus::Received);
    }

    /**
     * Indicate that the device is in service.
     */
    public function inService(): self
    {
        return $this->ofStatus(DeviceStatus::InService);
    }

    /**
     * Indicate that the device is ready for pickup.
     */
    public function ready(): self
    {
        return $this->ofStatus(DeviceStatus::Ready);
    }

    /**
     * Indicate that the device has been delivered.
     */
    public function delivered(): self
    {
        return $this->ofStatus(DeviceStatus::Delivered);
    }
}
