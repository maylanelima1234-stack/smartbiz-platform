<?php

namespace App\Core\Authorization\Services;

use App\Core\Authorization\Contracts\PermissionRepositoryContract;
use App\Core\Context\PlatformContext;
use BackedEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use LogicException;

class PermissionService
{
    public function __construct(
        private readonly PlatformContext $context,
        private readonly PermissionRepositoryContract $repository,
    ) {
    }

    public function allows(string|BackedEnum $permission): bool
    {
        if (! $this->context->hasCompany()) {
            return false;
        }

        if (
            $this->roles()
                ->intersect(
                    config(
                        'authorization.super_roles',
                        ['super_admin']
                    )
                )
                ->isNotEmpty()
        ) {
            return true;
        }

        return $this->permissions()
            ->contains(
                $this->normalize($permission)
            );
    }

    public function denies(string|BackedEnum $permission): bool
    {
        return ! $this->allows($permission);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->contains($role);
    }

    public function roles(): Collection
    {
        [$userId, $companyId] = $this->contextIds();

        $roles = Cache::remember(
            "smart-auth:v1:roles:company:{$companyId}:user:{$userId}",
            now()->addSeconds($this->cacheTtl()),
            fn (): array => $this->repository
                ->rolesFor($userId, $companyId)
                ->values()
                ->all()
        );

        return collect($roles);
    }

    public function permissions(): Collection
    {
        [$userId, $companyId] = $this->contextIds();

        $permissions = Cache::remember(
            "smart-auth:v1:permissions:company:{$companyId}:user:{$userId}",
            now()->addSeconds($this->cacheTtl()),
            fn (): array => $this->repository
                ->permissionsFor($userId, $companyId)
                ->values()
                ->all()
        );

        return collect($permissions);
    }

    public function flush(
        ?int $userId = null,
        ?int $companyId = null
    ): void {
        if ($userId === null || $companyId === null) {
            [$userId, $companyId] = $this->contextIds();
        }

        Cache::forget(
            "smart-auth:v1:roles:company:{$companyId}:user:{$userId}"
        );

        Cache::forget(
            "smart-auth:v1:permissions:company:{$companyId}:user:{$userId}"
        );
    }

    private function contextIds(): array
    {
        try {
            $userId = (int) $this->context
                ->user()
                ->getKey();
        } catch (LogicException) {
            throw new LogicException(
                'Não é possível verificar permissões antes de inicializar o PlatformContext.'
            );
        }

        $companyId = $this->context->companyId();

        if ($companyId === null) {
            throw new LogicException(
                'Não é possível verificar permissões sem uma empresa ativa.'
            );
        }

        return [
            $userId,
            (int) $companyId,
        ];
    }

    private function normalize(
        string|BackedEnum $permission
    ): string {
        return $permission instanceof BackedEnum
            ? (string) $permission->value
            : $permission;
    }

    private function cacheTtl(): int
    {
        return max(
            1,
            (int) config(
                'authorization.cache_ttl_seconds',
                300
            )
        );
    }
}