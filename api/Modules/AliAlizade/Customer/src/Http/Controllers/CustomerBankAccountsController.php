<?php

namespace AliAlizade\Customer\Http\Controllers;

use AliAlizade\Customer\Http\Resources\AccountResource;
use AliAlizade\Customer\Models\Account;
use App\Http\Controllers\Controller;

class CustomerBankAccountsController extends Controller
{
    public function show(Account $account)
    {

        return successResponse([
            'account' => new AccountResource(
                $account->loadMissing('customer')
            ),
        ]);
    }
}