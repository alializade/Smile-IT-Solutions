<?php

namespace AliAlizade\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
}
