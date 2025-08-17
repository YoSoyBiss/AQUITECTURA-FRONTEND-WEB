<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $role = strtolower((string) session('user_role'));
        $allowed = collect($roles)->map(fn($r)=>strtolower($r))->contains($role);

        if (!$allowed) {
            return redirect()->route('start.show')
                ->withErrors(['error' => 'No tienes permiso para acceder a ventas.']);
        }
        return $next($request);
    }
}
