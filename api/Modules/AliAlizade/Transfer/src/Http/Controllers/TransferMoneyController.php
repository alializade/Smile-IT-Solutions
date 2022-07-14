<?php

namespace AliAlizade\Transfer\Http\Controllers;

use AliAlizade\Customer\Models\Account;
use AliAlizade\Transfer\Enums\TransactionStatusEnum;
use AliAlizade\Transfer\Models\Transaction;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Laravel\Octane\Exceptions\TaskException;
use Laravel\Octane\Exceptions\TaskTimeoutException;
use Octane;
use Throwable;

class TransferMoneyController extends Controller
{
    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // todo: check it has enough amount
        try {
            DB::beginTransaction();

            [$from, $to, $transaction] = Octane::concurrently(
                [
                    fn() => Account::whereAccountNumber($data['from'])->decrement(
                        'current_amount',
                        $data['amount']
                    ),
                    fn() => Account::whereAccountNumber($data['to'])->increment(
                        'current_amount',
                        $data['amount']
                    ),
                    fn() => Transaction::create([
                        'tid'    => mt_rand(1000000, 9999999),
                        'from'   => $data['from'],
                        'to'     => $data['to'],
                        'amount' => $data['amount'],
                        'status' => TransactionStatusEnum::SUCCESS,
                    ]),
                ]);


            DB::commit();

        } catch (TaskException | TaskTimeoutException | Throwable $e) {
            DB::rollBack();

            abort(422, $e->getMessage());
        }


        return successResponse([
            'transaction' => $transaction,
            'from'        => $data['from'],
            'to'          => $data['to'],
        ]);
    }
}