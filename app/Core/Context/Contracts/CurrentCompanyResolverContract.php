<?php

namespace App\Core\Context\Contracts;

use App\Models\Company;
use App\Models\User;

interface CurrentCompanyResolverContract
{
    public function resolve(User $user): ?Company;

    public function switch(User $user, Company $company): void;

    public function clear(): void;
}