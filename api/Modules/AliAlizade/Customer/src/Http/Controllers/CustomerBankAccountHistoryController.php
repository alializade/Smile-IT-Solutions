<?php

namespace AliAlizade\Customer\Http\Controllers;

use AliAlizade\Customer\Models\Account;
use AliAlizade\Transfer\Http\Resources\TransactionResource;
use App\Http\Controllers\Controller;

class CustomerBankAccountHistoryController extends Controller
{
    public function index(Account $account)
    {
        return successResponse([
            'history' => TransactionResource::collection($account->history),
        ]);
    }
}