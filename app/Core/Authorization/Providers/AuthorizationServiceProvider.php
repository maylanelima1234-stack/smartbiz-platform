<?php

namespace App\Core\Authorization\Providers;

use App\Core\Authorization\Contracts\PermissionRepositoryContract;
use App\Core\Authorization\Repositories\EloquentPermissionRepository;
use App\Core\Authorization\Services\PermissionService;
use App\Core\Authorization\Services\SmartGate;
use Illuminate\Support\ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            PermissionRepositoryContract::class,
            EloquentPermissionRepository::class
        );

        $this->app->singleton(PermissionService::class);
        $this->app->singleton(SmartGate::class);
    }
}
