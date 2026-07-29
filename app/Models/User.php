<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name','email','password','phone','position','company_id','role','status','avatar','last_login_at',
    ];

    protected $hidden = ['password','remember_token'];

    protected $appends = ['avatar_url'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_user')
            ->withPivot(['status', 'joined_at', 'left_at'])
            ->withTimestamps();
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        // Aceita registros antigos salvos como URL completa, /storage/... ou caminho do disco public.
        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }

        if (str_starts_with($this->avatar, '/storage/')) {
            return url($this->avatar);
        }

        $path = ltrim(str_replace('storage/', '', $this->avatar), '/');

        return Storage::disk('public')->exists($path)
            ? Storage::disk('public')->url($path)
            : null;
    }

    public function isInternalSmartBizUser(): bool
    {
        return in_array($this->role, ['super_admin', 'admin_cto', 'admin_tm'], true);
    }

    public function hasMultiCompanyScope(): bool
    {
        return in_array($this->role, ['super_admin', 'admin_cto', 'admin_tm'], true);
    }

    public function hasGlobalAdministrationScope(): bool
    {
        return in_array($this->role, ['super_admin', 'admin_cto'], true);
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Admin',
            'admin' => 'Administrador',
            'admin_cto' => 'Administrador CTO',
            'admin_tm' => 'Administrador Tráfego',
            'manager', 'gestor' => 'Gestor',
            'cliente_gestor' => 'Gestor da Empresa',
            'commercial', 'comercial' => 'Comercial',
            'finance', 'financeiro' => 'Financeiro',
            'support' => 'Suporte',
            'client', 'cliente' => 'Cliente',
            'vendedor' => 'Vendedor',
            default => 'Usuário',
        };
    }
}
