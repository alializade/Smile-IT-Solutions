<?php

namespace AliAlizade\Customer\Database\Factories;

use AliAlizade\Customer\Models\Account;
use AliAlizade\Transfer\Enums\TransactionStatusEnum;
use AliAlizade\Transfer\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    #[ArrayShape([
        'tid'    => "int",
        'from'   => "\Illuminate\Support\HigherOrderCollectionProxy|int|mixed",
        'to'     => "\Illuminate\Support\HigherOrderCollectionProxy|int|mixed",
        'amount' => "int", 'status' => "\AliAlizade\Transfer\Enums\TransactionStatusEnum",
    ])]
    public function definition(): array
    {
        return [
            'tid'    => mt_rand(100000, 999999),
            'from'   => Account::factory()->create()->account_number,
            'to'     => Account::factory()->create()->account_number,
            'amount' => mt_rand(10, 5000),
            'status' => TransactionStatusEnum::SUCCESS,
        ];
    }
}
