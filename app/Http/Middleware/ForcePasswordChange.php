<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Si está logueado Y (su email no está verificado O su fecha de último acceso es null)
        // Asumiremos que null en email_verified_at significa "Debe cambiar contraseña"
        if ($user && is_null($user->email_verified_at)) {
            
            // Permitirle entrar solo a la ruta de cambiar contraseña y al logout
            if ($request->routeIs('password.change') || $request->routeIs('password.update_initial') || $request->routeIs('logout')) {
                return $next($request);
            }

            // Si intenta ir a otro lado, lo mandamos al formulario de cambio
            return redirect()->route('password.change');
        }

        return $next($request);
    }
}