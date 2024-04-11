<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::factory()->create();

        Company::factory()->withoutEmail()->create();

        Company::factory()->withoutPhone()->create();

        Company::factory()->withoutEmail()->withoutPhone()->create();

        Company::factory()->withoutVatNumber()->create();
    }
}
