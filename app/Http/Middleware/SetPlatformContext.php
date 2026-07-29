<?php

namespace App\Http\Middleware;

use App\Core\Context\Contracts\CurrentCompanyResolverContract;
use App\Core\Context\PlatformContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetPlatformContext
{
    public function __construct(
        private readonly CurrentCompanyResolverContract $companyResolver,
        private readonly PlatformContext $platformContext,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            $this->platformContext->reset();

            return $next($request);
        }

        $company = $this->companyResolver->resolve($user);

        $this->platformContext->initialize($user, $company);

        return $next($request);
    }
}