<?php

namespace App\Core\Licensing\Contracts;

use App\Models\Company;

interface LicenseRepositoryContract
{
    public function planFor(Company $company): string;
}
