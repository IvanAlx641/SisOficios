<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class FirstLoginController extends Controller
{
    /**
     * Muestra el formulario de cambio de contraseña obligatorio.
     */
    public function showChangeForm()
    {
        return view('auth.first-login');
    }

    /**
     * Procesa el cambio de contraseña inicial.
     */
    public function updatePassword(Request $request)
    {
        // Validaciones con mensajes en español
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ], [
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $user = Auth::user();

        // 1. Actualizar la contraseña
        $user->password = Hash::make($request->password);
        
        // 2. Marcar como VERIFICADO (Esto indica que ya cumplió el requisito)
        $user->email_verified_at = now();
        $user->fecha_ultimo_acceso = now();
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Contraseña actualizada correctamente. Bienvenido al sistema.');
    }
}