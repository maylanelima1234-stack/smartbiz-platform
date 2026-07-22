<?php

namespace App\Core\Licensing\Providers;

use App\Core\Licensing\Contracts\LicenseRepositoryContract;
use App\Core\Licensing\Repositories\EloquentLicenseRepository;
use App\Core\Licensing\Services\LicenseService;
use App\Core\Licensing\Services\SmartLicense;
use Illuminate\Support\ServiceProvider;

class LicensingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            LicenseRepositoryContract::class,
            EloquentLicenseRepository::class
        );

        $this->app->singleton(LicenseService::class);
        $this->app->singleton(SmartLicense::class);
    }
}
