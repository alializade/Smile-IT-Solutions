<?php

namespace AliAlizade\Customer\Database\Factories;

use AliAlizade\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    #[ArrayShape(['name' => "string"])]
    public function definition(): array
    {
        return [
            'name' => fake()->name,
        ];
    }
}
