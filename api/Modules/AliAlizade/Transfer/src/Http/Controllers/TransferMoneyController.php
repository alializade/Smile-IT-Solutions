<?php

namespace AliAlizade\Transfer\Http\Controllers;

use AliAlizade\Customer\Models\Account;
use AliAlizade\Transfer\Actions\SaveTransferRecordsAction;
use AliAlizade\Transfer\Http\Resources\TransactionResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferMoneyRequest;
use Throwable;

class TransferMoneyController extends Controller
{
    /** @throws Throwable */
    public function store(
        TransferMoneyRequest $request,
        SaveTransferRecordsAction $saveTransferRecordsAction
    ) {
        $this->checkIfItIsAValidTransfer($request);

        $transaction = $saveTransferRecordsAction->handle(
            input: $request->safe()->toArray()
        );

        return successResponse([
            'transaction' => new TransactionResource($transaction),
        ]);
    }

    private function checkIfItIsAValidTransfer(TransferMoneyRequest $request): void
    {
        $amount = $request->get('amount');

        [$from_account, $to_account] = Account::whereIn('account_number', [
            $request->get('from'),
            $request->get('to'),
        ])->get();

        abort_if(
            !$from_account->isAbleToTransfer($amount),
            422,
            trans('Insufficient Money!')
        );

        abort_if(
            $from_account->currency !== $to_account->currency,
            422,
            trans('Accounts don\'t have same the currency!')
        );
    }
}