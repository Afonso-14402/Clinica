<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica o papel do usuário
        if (auth()->check() && auth()->user()->role !== 'admin') {
            return redirect('/home')->with('error', 'Você não tem permissão para acessar esta página.');
        }

        return $next($request);
    }
}
