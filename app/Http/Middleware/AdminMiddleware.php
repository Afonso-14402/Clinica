<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            // Se não autenticado, redireciona para login
            return redirect()->route('login');
        }

        // Verifica se o role_id do usuário está permitido
        if (!in_array(Auth::user()->role_id, $roles)) {
            abort(403, 'Acesso não autorizado');
        }

        return $next($request);
    }
}
