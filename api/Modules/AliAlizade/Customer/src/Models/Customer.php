<?php

namespace AliAlizade\Customer\Models;

use AliAlizade\Customer\Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property HasMany $accounts
 */
class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }
}
