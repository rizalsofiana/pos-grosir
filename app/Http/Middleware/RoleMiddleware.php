<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        $allowedRoles = collect($roles)
            ->flatMap(fn (string $role): array => array_map('trim', explode(',', $role)))
            ->filter()
            ->values()
            ->all();

        $userRole = $user->role?->name;

        if (! in_array($userRole, $allowedRoles, true)) {
            abort(403, 'Forbidden.');
        }

        return $next($request);
    }
}
