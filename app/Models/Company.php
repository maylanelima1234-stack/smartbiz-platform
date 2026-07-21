<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'trade_name',
        'slug',
        'cnpj',
        'email',
        'phone',
        'owner',
        'website',
        'zip_code',
        'address',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'logo',
        'plan',
        'status',
        'notes',
        'created_by',
    ];
}
