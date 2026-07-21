<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmartNotification extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'title',
        'message',
        'type',
        'icon',
        'url',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public static function pushFor(User $user, string $title, string $message, string $type = 'system', ?string $url = null, ?string $icon = null): self
    {
        return self::create([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => $icon ?: strtoupper(substr($title, 0, 1)),
            'url' => $url,
        ]);
    }
}
