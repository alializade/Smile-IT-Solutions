<?php

namespace AliAlizade\Customer\Http\Controllers;

use AliAlizade\Customer\Models\Account;
use AliAlizade\Customer\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function store(Request $request)
    {
        $customer = Customer::query()->create($request->only('name'));

        $account = Account::query()->create([
            'customer_id'    => $customer->id,
            'account_number' => sprintf('%s%s', $customer->id, time()),
            'currency'       => $request->get('currency'),
            'current_amount' => $request->get('initial_deposit_amount'),
        ]);

        return response()->json([
            'status' => 'OK',
            'data'   => [
                'customer' => [
                    'name'    => $customer->name,
                    'account' => $account,
                ],
            ],
        ], 201);

    }
}