<?php

namespace App\Core\Authorization\Repositories;

use App\Core\Authorization\Contracts\PermissionRepositoryContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentPermissionRepository implements PermissionRepositoryContract
{
    public function rolesFor(int $userId, int $companyId): Collection
    {
        return DB::table('company_user_roles')
            ->join('company_user', 'company_user.id', '=', 'company_user_roles.company_user_id')
            ->join('roles', 'roles.id', '=', 'company_user_roles.role_id')
            ->where('company_user.user_id', $userId)
            ->where('company_user.company_id', $companyId)
            ->where('company_user.status', 'active')
            ->where('roles.status', 'active')
            ->whereNull('company_user.deleted_at')
            ->whereNull('roles.deleted_at')
            ->where(function ($query): void {
                $query->whereNull('company_user_roles.starts_at')
                    ->orWhere('company_user_roles.starts_at', '<=', now());
            })
            ->where(function ($query): void {
                $query->whereNull('company_user_roles.expires_at')
                    ->orWhere('company_user_roles.expires_at', '>', now());
            })
            ->pluck('roles.slug')
            ->unique()
            ->values();
    }

    public function permissionsFor(int $userId, int $companyId): Collection
    {
        $rolePermissions = DB::table('company_user_roles')
            ->join('company_user', 'company_user.id', '=', 'company_user_roles.company_user_id')
            ->join('roles', 'roles.id', '=', 'company_user_roles.role_id')
            ->join('role_permissions', 'role_permissions.role_id', '=', 'roles.id')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where('company_user.user_id', $userId)
            ->where('company_user.company_id', $companyId)
            ->where('company_user.status', 'active')
            ->where('roles.status', 'active')
            ->where('permissions.status', 'active')
            ->whereNull('company_user.deleted_at')
            ->whereNull('roles.deleted_at')
            ->whereNull('permissions.deleted_at')
            ->where(function ($query): void {
                $query->whereNull('company_user_roles.starts_at')
                    ->orWhere('company_user_roles.starts_at', '<=', now());
            })
            ->where(function ($query): void {
                $query->whereNull('company_user_roles.expires_at')
                    ->orWhere('company_user_roles.expires_at', '>', now());
            })
            ->pluck('permissions.slug')
            ->unique()
            ->values();

        $overrides = DB::table('user_permissions')
            ->join('permissions', 'permissions.id', '=', 'user_permissions.permission_id')
            ->where('user_permissions.user_id', $userId)
            ->where('user_permissions.company_id', $companyId)
            ->where('permissions.status', 'active')
            ->whereNull('user_permissions.deleted_at')
            ->whereNull('permissions.deleted_at')
            ->where(function ($query): void {
                $query->whereNull('user_permissions.starts_at')
                    ->orWhere('user_permissions.starts_at', '<=', now());
            })
            ->where(function ($query): void {
                $query->whereNull('user_permissions.expires_at')
                    ->orWhere('user_permissions.expires_at', '>', now());
            })
            ->get(['permissions.slug', 'user_permissions.allowed']);

        $permissions = $rolePermissions;

        foreach ($overrides as $override) {
            if ((bool) $override->allowed) {
                $permissions->push($override->slug);
                continue;
            }

            $permissions = $permissions->reject(
                fn (string $slug): bool => $slug === $override->slug
            );
        }

        return $permissions->unique()->values();
    }
}
