<?php

namespace App\Core\Authorization\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserAccessService
{
    public function sync(User $user, ?int $companyId, string $roleSlug, ?int $actorId = null): void
    {
        if ($companyId === null) {
            return;
        }

        $membershipId = DB::table('company_user')
            ->where('company_id', $companyId)
            ->where('user_id', $user->getKey())
            ->value('id');

        if ($membershipId === null) {
            $membershipId = DB::table('company_user')->insertGetId([
                'public_id' => (string) Str::ulid(),
                'company_id' => $companyId,
                'user_id' => $user->getKey(),
                'status' => 'active',
                'joined_at' => now(),
                'created_by' => $actorId,
                'updated_by' => $actorId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('company_user')->where('id', $membershipId)->update([
                'status' => 'active',
                'left_at' => null,
                'updated_by' => $actorId,
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
        }

        $roleId = DB::table('roles')
            ->whereNull('company_id')
            ->where('slug', $roleSlug)
            ->where('status', 'active')
            ->value('id');

        if ($roleId === null) {
            return;
        }

        DB::table('company_user_roles')->where('company_user_id', $membershipId)->delete();
        DB::table('company_user_roles')->insert([
            'company_user_id' => $membershipId,
            'role_id' => $roleId,
            'assigned_by' => $actorId,
            'starts_at' => now(),
            'expires_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
