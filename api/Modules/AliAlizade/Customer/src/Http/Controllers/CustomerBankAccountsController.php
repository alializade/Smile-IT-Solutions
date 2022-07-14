<?php

namespace AliAlizade\Customer\Http\Controllers;

use AliAlizade\Customer\Actions\Account\CreateABankAccountAction;
use AliAlizade\Customer\Actions\Customer\CreateACustomerAction;
use AliAlizade\Customer\Http\Requests\CreateAccountRequest;
use AliAlizade\Customer\Http\Requests\CreateCustomerRequest;
use AliAlizade\Customer\Http\Resources\AccountResource;
use AliAlizade\Customer\Http\Resources\CustomerResource;
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

    public function store(
        CreateAccountRequest $request,
        CreateABankAccountAction $createABankAccountAction
    ) {

        $customer = Customer::find($request->get('customer_id'));

        $account = $createABankAccountAction->handle($request->safe()->toArray(), $customer);

        return successResponse([
            'customer' => new CustomerResource($customer),
            'account'  => new AccountResource($account),
        ], 201);
    }
}