<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Device;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::all()->each(function (Customer $customer) {
            Device::factory()->forCustomer($customer)->create();
        });

        Customer::all()->random(10)->each(function (Customer $customer) {
            Device::factory()->forCustomer($customer)->withWarranty()->create();
        });
    }
}
