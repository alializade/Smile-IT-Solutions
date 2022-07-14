<?php

namespace AliAlizade\Transfer\Models;

use AliAlizade\Transfer\Enums\TransactionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['tid', 'from', 'to', 'amount', 'status'];

    protected $casts = [
        'status' => TransactionStatusEnum::class,
        'amount' => 'float'
    ];
}
