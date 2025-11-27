<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado y tiene rol de administrador (role = 1)
        if (Auth::check() && Auth::user()->role <= 1) {
            return $next($request);
        }
        
        // Redireccionar a la página principal si no es administrador
        return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
    }
}