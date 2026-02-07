<?php

namespace Database\Seeders;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Models\Customer;
use App\Models\Device;
use Database\Fixtures\DeviceFixture;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // For each customer, create 1-3 devices
        Customer::all()->each(function ($customer) {
            DeviceType::all()
                ->random(rand(1, 3))
                ->each(function ($type) use ($customer) {
                    Device::factory()
                        ->forCustomer($customer)
                        ->ofType($type)
                        ->ofStatus(DeviceStatus::all()->random())
                        ->state(DeviceFixture::deviceByType())
                        ->create();
                });
        });
    }
}
