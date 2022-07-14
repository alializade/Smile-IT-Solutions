<?php

namespace AliAlizade\Transfer\Http\Controllers;

use AliAlizade\Customer\Models\Account;
use AliAlizade\Customer\Models\Customer;
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
        abort_unless(
            $this->isAValidTransfer($request),
            422,
            trans('Insufficient Money!')
        );

        $transaction = $saveTransferRecordsAction->handle(
            input: $request->safe()->toArray()
        );

        return successResponse([
            'transaction' => new TransactionResource($transaction),
        ]);
    }

    /**
     * @param  TransferMoneyRequest  $request
     *
     * @return bool
     */
    private function isAValidTransfer(TransferMoneyRequest $request): bool
    {
        return Account::withNumber($request->get('from'))->hasAbleToTransfer($request->get('amount'));
    }
}