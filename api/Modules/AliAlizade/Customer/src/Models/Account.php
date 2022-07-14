<?php

namespace AliAlizade\Customer\Models;

use AliAlizade\Customer\Database\Factories\AccountFactory;
use AliAlizade\Customer\Database\Factories\TransactionFactory;
use AliAlizade\Customer\Enums\CurrencyEnum;
use AliAlizade\Transfer\Models\Transaction;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property $history;
 * @property $withdraw_transactions;
 * @property $deposit_transactions;
 */
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

    protected function history(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->withdrawTransactions->merge($this->depositTransactions)
        );
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

    public function withdrawTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'from', 'account_number');
    }

    public function depositTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'to', 'account_number');
    }

    protected static function newFactory(): AccountFactory
    {
        return AccountFactory::new();
    }
}
