<?php

namespace AliAlizade\Transfer\Actions;

use AliAlizade\Customer\Models\Account;

class ProcessAccountDepositAction
{
    public function handle(int $account_number, float $amount): int
    {
        return Account::whereAccountNumber($account_number)->increment(
            'current_amount',
            $amount
        );
    }
}