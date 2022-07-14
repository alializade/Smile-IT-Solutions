<?php

namespace AliAlizade\Customer\Models;

use AliAlizade\Customer\Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $total_balance
 * @property HasMany $accounts
 */
class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected function totalBalance(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->accounts->sum('current_amount')
        );
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }
}
