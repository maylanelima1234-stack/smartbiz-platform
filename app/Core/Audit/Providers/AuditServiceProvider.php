<?php

namespace App\Core\Audit\Providers;

use App\Core\Audit\Contracts\AuditRepositoryContract;
use App\Core\Audit\Repositories\EloquentAuditRepository;
use App\Core\Audit\Services\AuditService;
use App\Core\Audit\Services\SmartAudit;
use Illuminate\Support\ServiceProvider;

class AuditServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            AuditRepositoryContract::class,
            EloquentAuditRepository::class
        );

        $this->app->singleton(AuditService::class);
        $this->app->singleton(SmartAudit::class);
    }
}
