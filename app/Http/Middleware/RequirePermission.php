<?php

namespace App\Http\Middleware;

use App\Core\Authorization\Facades\SmartGate;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RequirePermission
{
    /**
     * Protege uma rota por permissão sem depender apenas da visibilidade do menu.
     * Adm. CTO e Super Admin mantêm acesso total à plataforma.
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if ($user === null) {
            abort(401);
        }

        if (in_array($user->role, ['super_admin', 'admin_cto'], true)) {
            return $next($request);
        }

        if ($permissions === []) {
            throw new AuthorizationException('Permissão não informada para esta rota.');
        }

        try {
            foreach ($permissions as $permission) {
                if (SmartGate::allows($permission)) {
                    return $next($request);
                }
            }
        } catch (Throwable) {
            // Contexto ausente ou inconsistente nunca deve liberar acesso por acidente.
        }

        throw new AuthorizationException('Você não possui permissão para acessar este recurso.');
    }
}
