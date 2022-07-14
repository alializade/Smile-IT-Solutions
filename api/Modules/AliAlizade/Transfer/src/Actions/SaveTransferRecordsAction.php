<?php

namespace AliAlizade\Transfer\Actions;

use AliAlizade\Transfer\Models\Transaction;
use Arr;
use DB;
use Laravel\Octane\Exceptions\TaskException;
use Laravel\Octane\Exceptions\TaskTimeoutException;
use Laravel\Octane\Facades\Octane;
use Throwable;

class SaveTransferRecordsAction
{
    /**
     * @throws Throwable
     */
    public function handle(array $input): Transaction
    {

        $data = Arr::only($input, ['from', 'to', 'amount']);

        try {
            DB::beginTransaction();
            [$_, $_, $transaction] = Octane::concurrently(
                [
                    fn() => resolve(ProcessAccountWithdrawAction::class)->handle(
                        account_number: $data['from'],
                        amount        : $data['amount']
                    ),
                    fn() => resolve(ProcessAccountDepositAction::class)->handle(
                        account_number: $data['to'],
                        amount        : $data['amount']
                    ),
                    fn() => resolve(SaveTransactionAction::class)->handle(
                        input: $data
                    ),
                ]);
            DB::commit();

        } catch (TaskException | TaskTimeoutException | Throwable $e) {
            DB::rollBack();
            abort(422, $e->getMessage());
        }

        return $transaction;
    }

}