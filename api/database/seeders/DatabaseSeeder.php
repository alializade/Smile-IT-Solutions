<?php

namespace Database\Seeders;

use AliAlizade\Customer\Database\Seeders\CustomersSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(CustomersSeeder::class);
    }
}
