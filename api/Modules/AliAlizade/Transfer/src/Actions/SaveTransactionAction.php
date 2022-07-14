<?php

namespace AliAlizade\Transfer\Actions;

use AliAlizade\Transfer\Enums\TransactionStatusEnum;
use AliAlizade\Transfer\Models\Transaction;
use Illuminate\Support\Arr;

class SaveTransactionAction
{

    public function handle(array $input): Transaction
    {
        $data = Arr::only($input, ['from', 'to', 'amount']);

        return Transaction::create([
            'tid'    => mt_rand(1000000, 9999999),
            'from'   => $data['from'],
            'to'     => $data['to'],
            'amount' => $data['amount'],
            'status' => TransactionStatusEnum::SUCCESS,
        ]);
    }
}