<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CustomerSeeder::class,
            DeviceSeeder::class,
            TicketSeeder::class,
            TaskSeeder::class,
            OrderSeeder::class,
            InvoiceSeeder::class,
            AdjustmentSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
