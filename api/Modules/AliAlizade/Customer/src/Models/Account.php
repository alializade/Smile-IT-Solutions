<?php

namespace AliAlizade\Customer\Models;

use AliAlizade\Customer\Database\Factories\AccountFactory;
use AliAlizade\Customer\Enums\CurrencyEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'account_number', 'currency', 'current_amount'];

    protected $casts = [
        'current_amount' => 'float',
        'currency'       => CurrencyEnum::class,
    ];

    public function getRouteKeyName(): string
    {
        return 'account_number';
    }


    public function isAbleToTransfer(float $transfer_amount): bool
    {
        return $this->current_amount >= $transfer_amount;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)
                    ->withDefault([
                        'name' => 'UNDEFINED',
                    ]);
    }

    protected static function newFactory(): AccountFactory
    {
        return AccountFactory::new();
    }
}
