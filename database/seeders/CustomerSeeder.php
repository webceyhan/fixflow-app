<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory(10)->create();

        Customer::factory(10)->withoutEmail()->create();

        Customer::factory(10)->withoutPhone()->create();

        Customer::factory(10)->withoutAddress()->create();

        Company::all()->each(function (Company $company) {
            Customer::factory()->forCompany($company)->create();
        });
    }
}
