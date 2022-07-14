<?php

namespace AliAlizade\Customer\Http\Controllers;

use AliAlizade\Customer\Actions\Account\CreateABankAccountAction;
use AliAlizade\Customer\Actions\Customer\CreateACustomerAction;
use AliAlizade\Customer\Http\Requests\CreateCustomerRequest;
use AliAlizade\Customer\Http\Resources\AccountResource;
use AliAlizade\Customer\Http\Resources\CustomerResource;
use App\Http\Controllers\Controller;

class CustomersController extends Controller
{

    public function store(
        CreateCustomerRequest $request,
        CreateACustomerAction $createACustomerAction,
        CreateABankAccountAction $createABankAccountAction
    ) {

        $customer = $createACustomerAction->handle($request->safe()->toArray());

        $account = $createABankAccountAction->handle($request->safe()->toArray(), $customer);

        return successResponse([
            'customer' => new CustomerResource($customer),
            'account'  => new AccountResource($account),
        ], 201);
    }
}