<?php

namespace AliAlizade\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'account_number', 'currency', 'current_amount'];
    protected $casts    = [
        'current_amount' => 'float',
    ];
}
