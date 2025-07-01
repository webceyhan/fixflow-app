<?php

namespace Database\Factories;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 *
 * @method static hasTickets(int $count = 1, array $attributes = [])
 */
class DeviceFactory extends Factory
{
    const VERSIONS = [
        'iMac' => ['21"', '27"'],
        'Mac' => ['Mini', 'Pro', 'Studio'],
        'MacBook' => ['Air', 'Pro'],
        'iPad' => ['Mini', 'Air', 'Pro'],
        'iPhone' => ['SE', 'X', 'Pro', 'Max', 'Pro Max'],
        'Apple Watch' => ['Series 6', 'Series 7', 'SE'],
        'Galaxy' => ['S10', 'S20', 'S21'],
        'Galaxy Tab' => ['A', 'S', 'E'],
        'Galaxy Watch' => ['Active', 'Classic'],
        'Vaio' => ['Pro', 'Slim'],
        'PlayStation' => ['4', '5'],
        'Pavilion' => ['Gaming', 'Business'],
        'Deskjet' => ['1000', '2000'],
        'Thinkpad' => ['X1', 'T'],
        'Go Comfort' => ['5"', '6"'],
    ];

    const BRANDS = [
        'iMac' => 'Apple',
        'Mac' => 'Apple',
        'MacBook' => 'Apple',
        'iPad' => 'Apple',
        'iPhone' => 'Apple',
        'Apple Watch' => 'Apple',
        'Galaxy' => 'Samsung',
        'Galaxy Tab' => 'Samsung',
        'Galaxy Watch' => 'Samsung',
        'Vaio' => 'Sony',
        'PlayStation' => 'Sony',
        'Pavilion' => 'Hp',
        'Deskjet' => 'Hp',
        'Thinkpad' => 'Lenovo',
        'Go Comfort' => 'TomTom',
    ];

    const TYPES = [
        'iMac' => DeviceType::Desktop,
        'Mac' => DeviceType::Desktop,
        'MacBook' => DeviceType::Laptop,
        'iPad' => DeviceType::Tablet,
        'iPhone' => DeviceType::Phone,
        'Apple Watch' => DeviceType::Wearable,
        'Galaxy' => DeviceType::Phone,
        'Galaxy Tab' => DeviceType::Tablet,
        'Galaxy Watch' => DeviceType::Wearable,
        'Vaio' => DeviceType::Laptop,
        'PlayStation' => DeviceType::Other,
        'Pavilion' => DeviceType::Desktop,
        'Deskjet' => DeviceType::Other,
        'Thinkpad' => DeviceType::Laptop,
        'Go Comfort' => DeviceType::Other,
    ];

    public function definition(): array
    {
        // Generate random model name and version
        $name = fake()->randomElement(array_keys(self::VERSIONS));
        $version = fake()->randomElement(self::VERSIONS[$name]);

        return [
            'customer_id' => Customer::factory(),
            'model' => "{$name} {$version}",
            'brand' => self::BRANDS[$name],
            'serial_number' => fake()->uuid(),
            'purchase_date' => now()->subYears(1),
            'warranty_expire_date' => now()->addYears(1),
            'type' => self::TYPES[$name],
            'status' => fake()->randomElement(DeviceStatus::values()),
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
     * Indicate that the device has no brand.
     */
    public function withoutBrand(): self
    {
        return $this->state(fn (array $attributes) => [
            'brand' => null,
        ]);
    }

    /**
     * Indicate that the device has no serial number.
     */
    public function withoutSerialNumber(): self
    {
        return $this->state(fn (array $attributes) => [
            'serial_number' => null,
        ]);
    }

    /**
     * Indicate that the device has no purchase date.
     */
    public function withoutPurchaseDate(): self
    {
        return $this->state(fn (array $attributes) => [
            'purchase_date' => null,
            'warranty_expire_date' => null,
        ]);
    }

    /**
     * Indicate that the device is out of warranty.
     */
    public function outOfWarranty(): self
    {
        return $this->state(fn (array $attributes) => [
            'purchase_date' => now()->subYears(2),
            'warranty_expire_date' => now()->subYear(),
        ]);
    }

    /**
     * Indicate that the device is of the given type.
     */
    public function ofType(DeviceType $type): self
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }

    /**
     * Indicate that the device is of the given status.
     */
    public function ofStatus(DeviceStatus $status): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }
}
