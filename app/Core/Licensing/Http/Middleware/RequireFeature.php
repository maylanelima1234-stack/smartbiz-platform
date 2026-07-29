<?php

namespace App\Core\Licensing\Http\Middleware;

use App\Core\Licensing\Services\SmartLicense;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireFeature
{
    public function __construct(
        private readonly SmartLicense $license,
    ) {
    }

    public function handle(
        Request $request,
        Closure $next,
        string $feature
    ): Response {
        abort_unless(
            $this->license->allows($feature),
            403,
            'Este recurso não está disponível no plano atual.'
        );

        return $next($request);
    }
}
