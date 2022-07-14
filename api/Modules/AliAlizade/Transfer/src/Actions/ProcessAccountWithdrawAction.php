<?php

namespace AliAlizade\Transfer\Actions;

use AliAlizade\Customer\Models\Account;

class ProcessAccountWithdrawAction
{
    public function handle(int $account_number, float $amount): int
    {
        return Account::whereAccountNumber($account_number)->decrement(
            'current_amount',
            $amount
        );
    }
}