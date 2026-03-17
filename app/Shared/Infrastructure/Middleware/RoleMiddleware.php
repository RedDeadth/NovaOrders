<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        if (!in_array($user->role->value, $roles)) {
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción',
                'required_roles' => $roles,
            ], 403);
        }

        return $next($request);
    }
}
