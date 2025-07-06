<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Device;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Seed the devices table.
     */
    public function run(): void
    {
        Customer::all()->each(function ($customer) {
            // Create a device factory for the customer
            $deviceFactory = Device::factory()->forCustomer($customer);

            // Create a device with various states
            match (rand(1, 5)) {
                1 => $deviceFactory->create(),
                2 => $deviceFactory->withoutBrand()->create(),
                3 => $deviceFactory->withoutSerialNumber()->create(),
                4 => $deviceFactory->withoutPurchaseDate()->create(),
                5 => $deviceFactory->withoutWarranty()->create(),
            };
        });
    }
}
