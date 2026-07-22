<?php

namespace App\Core\Licensing\Http\Middleware;

use App\Core\Licensing\Services\SmartLicense;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireLimit
{
    public function __construct(
        private readonly SmartLicense $license,
    ) {
    }

    public function handle(
        Request $request,
        Closure $next,
        string $limit,
        string $usedAttribute
    ): Response {
        $used = (int) $request->attributes->get($usedAttribute, 0);

        abort_unless(
            $this->license->withinLimit($limit, $used),
            403,
            'O limite do plano atual foi atingido.'
        );

        return $next($request);
    }
}
