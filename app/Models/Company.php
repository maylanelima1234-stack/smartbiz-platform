<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user')
            ->withPivot(['status', 'joined_at', 'left_at'])
            ->withTimestamps();
    }

    public function directUsers(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
