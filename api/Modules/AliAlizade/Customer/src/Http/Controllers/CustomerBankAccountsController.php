<?php

namespace AliAlizade\Customer\Http\Controllers;

use AliAlizade\Customer\Http\Resources\AccountResource;
use AliAlizade\Customer\Models\Account;
use AliAlizade\Customer\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CustomerBankAccountsController extends Controller
{
    public function index(Customer $customer): JsonResponse
    {
        return successResponse([
            'total_balance' => $customer->total_balance,
            'accounts'      => AccountResource::collection($customer->accounts),
        ]);
    }

    public function show(Account $account): JsonResponse
    {
        return successResponse([
            'account' => new AccountResource(
                $account->loadMissing('customer')
            ),
        ]);
    }
}