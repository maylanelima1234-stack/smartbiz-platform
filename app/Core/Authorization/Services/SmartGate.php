<?php

namespace App\Core\Authorization\Services;

use BackedEnum;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Collection;

class SmartGate
{
    public function __construct(
        private readonly PermissionService $permissions,
    ) {
    }

    public function allows(string|BackedEnum $permission): bool
    {
        return $this->permissions->allows($permission);
    }

    public function denies(string|BackedEnum $permission): bool
    {
        return $this->permissions->denies($permission);
    }

    public function authorize(string|BackedEnum $permission): void
    {
        if ($this->denies($permission)) {
            throw new AuthorizationException(
                'Você não possui permissão para executar esta ação.'
            );
        }
    }

    public function hasRole(string $role): bool
    {
        return $this->permissions->hasRole($role);
    }

    public function roles(): Collection
    {
        return $this->permissions->roles();
    }

    public function permissions(): Collection
    {
        return $this->permissions->permissions();
    }

    public function flush(): void
    {
        $this->permissions->flush();
    }
}
