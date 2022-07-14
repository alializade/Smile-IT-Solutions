<?php

namespace AliAlizade\Customer\Actions\Customer;

use AliAlizade\Customer\Models\Customer;
use Illuminate\Support\Arr;

class CreateACustomerAction
{
    public function handle(array $data): Customer
    {
        $attributes = Arr::only($data, ['name']);

        return Customer::query()->create($attributes);
    }
}