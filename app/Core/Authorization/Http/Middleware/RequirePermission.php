<?php

namespace App\Core\Authorization\Http\Middleware;

use App\Core\Authorization\Services\SmartGate;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePermission
{
    public function __construct(
        private readonly SmartGate $gate,
    ) {
    }

    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response {
        $this->gate->authorize($permission);

        return $next($request);
    }
}
