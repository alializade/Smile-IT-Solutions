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

    public function isAbleToTransfer(int $transfer_amount): bool
    {
        return $this->current_amount >= $transfer_amount;
    }

    protected static function newFactory(): AccountFactory
    {
        return AccountFactory::new();
    }
}
