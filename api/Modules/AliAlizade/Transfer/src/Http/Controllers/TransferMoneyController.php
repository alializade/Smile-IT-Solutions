<?php

namespace AliAlizade\Transfer\Http\Controllers;

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
        
        $transaction = $saveTransferRecordsAction->handle(
            input: $request->safe()->toArray()
        );

        return successResponse([
            'transaction' => new TransactionResource($transaction),
        ]);
    }
}