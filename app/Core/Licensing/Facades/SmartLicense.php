<?php

namespace App\Core\Licensing\Facades;

use App\Core\Licensing\Services\SmartLicense as SmartLicenseService;
use Illuminate\Support\Facades\Facade;

class SmartLicense extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SmartLicenseService::class;
    }
}
