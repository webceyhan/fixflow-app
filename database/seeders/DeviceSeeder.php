<?php

namespace Database\Seeders;

use App\Enums\DeviceStatus;
use App\Models\Customer;
use App\Models\Device;
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
            DeviceStatus::all()->random(rand(1, 3))->each(function ($status) use ($customer) {
                Device::factory()->forCustomer($customer)->ofStatus($status)->create();
            });
        });
    }
}
