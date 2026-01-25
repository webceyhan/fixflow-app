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

    private const VERSIONS = [
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

    private const BRANDS = [
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

    private const TYPES = [
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

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate random model name and version
        $name = fake()->randomElement(array_keys(self::VERSIONS));
        $version = fake()->randomElement(self::VERSIONS[$name]);
        $type = self::TYPES[$name];

        // Determine if the device is mobile (phone or tablet)
        $isMobile = in_array($type, [DeviceType::Phone, DeviceType::Tablet]);

        return [
            'customer_id' => Customer::factory(),
            'model' => "{$name} {$version}",
            'brand' => self::BRANDS[$name],
            'color' => $isMobile ? fake()->safeColorName() : null,
            'imei' => $isMobile ? fake()->numerify('###############') : null,
            'serial_number' => fake()->optional(0.7)->regexify('[A-Z0-9]{10,15}'),
            'passcode' => fake()->optional(0.6)->numerify('####'),
            'purchase_date' => null,
            'warranty_expire_date' => null,
            'note' => null,
            'type' => $type,
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
    public function withWarranty(): self
    {
        return $this->state(function (array $attributes) {
            $purchaseDate = fake()->dateTimeBetween('-3 years', 'now');
            $warrantyExpireDate = fake()->dateTimeBetween($purchaseDate, '+2 years');

            return [
                'purchase_date' => $purchaseDate,
                'warranty_expire_date' => $warrantyExpireDate,
            ];
        });
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
