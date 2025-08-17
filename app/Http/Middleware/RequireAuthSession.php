<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireAuthSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('user_id')) {
            return redirect()->route('users.login')
                ->withErrors(['error' => 'Debes iniciar sesión.']);
        }
        return $next($request);
    }
}
