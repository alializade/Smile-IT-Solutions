<?php

namespace AliAlizade\Customer\Http\Controllers;

use AliAlizade\Customer\Models\Account;
use App\Http\Controllers\Controller;

class CustomerBankAccountHistoryController extends Controller
{
    public function index(Account $account)
    {
        return successResponse([
            'history' => $account->history,
        ]);
    }
}