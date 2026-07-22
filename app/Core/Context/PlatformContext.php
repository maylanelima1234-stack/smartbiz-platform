<?php

namespace App\Core\Context;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use LogicException;

class PlatformContext
{
    private ?User $user = null;

    private ?Company $company = null;

    private Collection $roles;

    private Collection $permissions;

    public function __construct()
    {
        $this->roles = collect();
        $this->permissions = collect();
    }

    public function initialize(User $user, ?Company $company): void
    {
        $this->user = $user;
        $this->company = $company;

        if ($company === null) {
            $this->roles = collect();
            $this->permissions = collect();

            return;
        }

        $this->roles = $this->loadRoles($user, $company);
        $this->permissions = $this->loadPermissions($user, $company);
    }

    public function user(): User
    {
        if ($this->user === null) {
            throw new LogicException(
                'O PlatformContext ainda não foi inicializado.'
            );
        }

        return $this->user;
    }

    public function company(): ?Company
    {
        return $this->company;
    }

    public function companyId(): ?int
    {
        return $this->company?->getKey();
    }

    public function roles(): Collection
    {
        return $this->roles;
    }

    public function permissions(): Collection
    {
        return $this->permissions;
    }

    public function hasCompany(): bool
    {
        return $this->company !== null;
    }

    public function hasRole(string $role): bool
    {
        return $this->roles->contains($role);
    }

    public function can(string $permission): bool
    {
        return $this->permissions->contains($permission);
    }

    public function reset(): void
    {
        $this->user = null;
        $this->company = null;
        $this->roles = collect();
        $this->permissions = collect();
    }

    private function loadRoles(User $user, Company $company): Collection
    {
        return DB::table('company_user_roles')
            ->join(
                'company_user',
                'company_user.id',
                '=',
                'company_user_roles.company_user_id'
            )
            ->join(
                'roles',
                'roles.id',
                '=',
                'company_user_roles.role_id'
            )
            ->where('company_user.user_id', $user->getKey())
            ->where('company_user.company_id', $company->getKey())
            ->where('company_user.status', 'active')
            ->where('roles.status', 'active')
            ->whereNull('company_user.deleted_at')
            ->whereNull('roles.deleted_at')
            ->where(function ($query): void {
                $query
                    ->whereNull('company_user_roles.starts_at')
                    ->orWhere(
                        'company_user_roles.starts_at',
                        '<=',
                        now()
                    );
            })
            ->where(function ($query): void {
                $query
                    ->whereNull('company_user_roles.expires_at')
                    ->orWhere(
                        'company_user_roles.expires_at',
                        '>',
                        now()
                    );
            })
            ->pluck('roles.slug')
            ->unique()
            ->values();
    }

    private function loadPermissions(
        User $user,
        Company $company
    ): Collection {
        $rolePermissions = DB::table('company_user_roles')
            ->join(
                'company_user',
                'company_user.id',
                '=',
                'company_user_roles.company_user_id'
            )
            ->join(
                'roles',
                'roles.id',
                '=',
                'company_user_roles.role_id'
            )
            ->join(
                'role_permissions',
                'role_permissions.role_id',
                '=',
                'roles.id'
            )
            ->join(
                'permissions',
                'permissions.id',
                '=',
                'role_permissions.permission_id'
            )
            ->where('company_user.user_id', $user->getKey())
            ->where('company_user.company_id', $company->getKey())
            ->where('company_user.status', 'active')
            ->where('roles.status', 'active')
            ->where('permissions.status', 'active')
            ->whereNull('company_user.deleted_at')
            ->whereNull('roles.deleted_at')
            ->whereNull('permissions.deleted_at')
            ->where(function ($query): void {
                $query
                    ->whereNull('company_user_roles.starts_at')
                    ->orWhere(
                        'company_user_roles.starts_at',
                        '<=',
                        now()
                    );
            })
            ->where(function ($query): void {
                $query
                    ->whereNull('company_user_roles.expires_at')
                    ->orWhere(
                        'company_user_roles.expires_at',
                        '>',
                        now()
                    );
            })
            ->pluck('permissions.slug');

        $overrides = DB::table('user_permissions')
            ->join(
                'permissions',
                'permissions.id',
                '=',
                'user_permissions.permission_id'
            )
            ->where('user_permissions.user_id', $user->getKey())
            ->where('user_permissions.company_id', $company->getKey())
            ->where('permissions.status', 'active')
            ->whereNull('user_permissions.deleted_at')
            ->whereNull('permissions.deleted_at')
            ->where(function ($query): void {
                $query
                    ->whereNull('user_permissions.starts_at')
                    ->orWhere(
                        'user_permissions.starts_at',
                        '<=',
                        now()
                    );
            })
            ->where(function ($query): void {
                $query
                    ->whereNull('user_permissions.expires_at')
                    ->orWhere(
                        'user_permissions.expires_at',
                        '>',
                        now()
                    );
            })
            ->get([
                'permissions.slug',
                'user_permissions.allowed',
            ]);

        $permissions = $rolePermissions
            ->unique()
            ->values();

        foreach ($overrides as $override) {
            if ((bool) $override->allowed) {
                $permissions->push($override->slug);

                continue;
            }

            $permissions = $permissions->reject(
                fn (string $slug): bool => $slug === $override->slug
            );
        }

        return $permissions
            ->unique()
            ->values();
    }
}