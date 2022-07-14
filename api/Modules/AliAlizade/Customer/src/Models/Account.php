<?php

namespace AliAlizade\Customer\Models;

use AliAlizade\Customer\Database\Factories\AccountFactory;
use AliAlizade\Customer\Enums\CurrencyEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'account_number', 'currency', 'current_amount'];

    protected $casts = [
        'current_amount' => 'float',
        'currency'       => CurrencyEnum::class,
    ];

    public static function withNumber(int $account_number): Account
    {
        return Account::firstWhere('account_number', $account_number);
    }

    public function hasAbleToTransfer(int $transfer_amount): bool
    {
        return $this->current_amount >= $transfer_amount;
    }

    protected static function newFactory(): AccountFactory
    {
        return AccountFactory::new();
    }
}
