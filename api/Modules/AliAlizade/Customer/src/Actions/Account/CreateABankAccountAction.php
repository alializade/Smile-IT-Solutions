<?php

namespace AliAlizade\Customer\Actions\Account;

use AliAlizade\Customer\Models\Account;
use AliAlizade\Customer\Models\Customer;
use Illuminate\Support\Arr;

class CreateABankAccountAction
{
    public function handle(array $data, Customer $customer): Account
    {
        $data = Arr::only($data, ['currency', 'initial_deposit_amount']);

        return Account::query()->create([
            'customer_id'    => $customer->id,
            'account_number' => sprintf('%s%s', $customer->id, time()),
            'currency'       => $data['currency'],
            'current_amount' => $data['initial_deposit_amount'],
        ]);
    }
}