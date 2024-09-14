<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect('login');
        }

        // Verificar si el usuario tiene el rol requerido
        $user = Auth::user();
        if ($user->role->name !== 'admin') {
            //abort(403, 'No tienes permiso para acceder a esta página.');
            return redirect()->route('home');//
        }

        return $next($request);
    }
}
