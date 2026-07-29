<?php

namespace App\Core\Context\Providers;

use App\Core\Context\Contracts\CurrentCompanyResolverContract;
use App\Core\Context\CurrentCompanyResolver;
use App\Core\Context\PlatformContext;
use Illuminate\Support\ServiceProvider;

class PlatformContextServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            CurrentCompanyResolverContract::class,
            CurrentCompanyResolver::class
        );

        $this->app->scoped(
            PlatformContext::class,
            fn (): PlatformContext => new PlatformContext()
        );
    }
}