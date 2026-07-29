<?php

namespace App\Core\Licensing\Repositories;

use App\Core\Licensing\Contracts\LicenseRepositoryContract;
use App\Models\Company;

class EloquentLicenseRepository implements LicenseRepositoryContract
{
    public function planFor(Company $company): string
    {
        return (string) ($company->plan ?: config('licensing.default_plan', 'starter'));
    }
}
