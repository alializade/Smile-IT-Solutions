<?php

namespace AliAlizade\Customer\Database\Factories;

use AliAlizade\Customer\Enums\CurrencyEnum;
use AliAlizade\Customer\Models\Account;
use AliAlizade\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    #[ArrayShape([
        'customer_id'    => "\Closure", 'account_number' => "int",
        'currency'       => "\AliAlizade\Customer\Enums\CurrencyEnum",
        'current_amount' => "int",
    ])]
    public function definition(): array
    {
        return [
            'customer_id'    => fn() => Customer::factory(),
            'account_number' => fake()->randomNumber(5),
            'currency'       => CurrencyEnum::USD,
            'current_amount' => 0,
        ];
    }
}
