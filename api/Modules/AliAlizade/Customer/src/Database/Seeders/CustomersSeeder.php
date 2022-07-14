<?php

namespace AliAlizade\Customer\Database\Seeders;

use AliAlizade\Customer\Models\Account;
use AliAlizade\Customer\Models\Customer;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CustomersSeeder extends Seeder
{
    public function run(): void
    {
        $data = collect([
            ['name' => 'Arisha Barron', 'initial_deposit_amount' => 20, 'currency' => 'USD'],
            ['name' => 'Branden Gibson', 'initial_deposit_amount' => 40, 'currency' => 'USD'],
            ['name' => 'Rhonda Church', 'initial_deposit_amount' => 520, 'currency' => 'USD'],
            ['name' => 'Georgina Hazel', 'initial_deposit_amount' => 300, 'currency' => 'USD'],
        ]);

        if (!Schema::hasTable('customers')) {
            return;
        }

        $data->each(function ($record) {

            $customer = Customer::create(['name' => $record['name']]);

            Account::create([
                'customer_id'    => $customer->id,
                'account_number' => sprintf('%s%s', $customer->id, time()),
                'current_amount' => $record['initial_deposit_amount'],
                'currency'       => $record['currency'],
            ]);
        });

    }
}
