<?php

namespace App\Core\Authorization\Contracts;

use Illuminate\Support\Collection;

interface PermissionRepositoryContract
{
    public function rolesFor(int $userId, int $companyId): Collection;

    public function permissionsFor(int $userId, int $companyId): Collection;
}
