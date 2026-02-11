<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\RecuperarContrasena;

class AuthController extends Controller
{
    // --- LOGIN ---
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ],
            [
                'email.required' => 'El correo es obligatorio',
                'email.email' => 'El correo no es válido',
                'password.required' => 'La contraseña es obligatoria',
            ]
        );

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Verificar si está inactivo
            if ($user->inactivo == 'X') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                //Mensaje para usuarios inactivos
                return back()->withErrors(['email' => 'Las credenciales proporcionadas no coinciden con nuestros registros']);
            }

            $request->session()->regenerate();
            $user->update(['fecha_ultimo_acceso' => now()]);

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // --- RECUPERACIÓN DE CONTRASEÑA ---

    // 1. Mostrar formulario "Olvidé mi contraseña"
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // 2. Enviar el correo con el token
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email'], [
            'email.exists' => 'No encontramos ningún usuario con este correo electrónico.',
            'email.required'=>'Favor de ingresar su correo electronico'
        ]);

        // Generar token
        $token = Str::random(64);

        // Guardar token en tabla password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Enviar Correo
        Mail::to($request->email)->send(new RecuperarContrasena($token, $request->email));

        return back()->with('status', '¡Enlace enviado! Revisa tu bandeja de entrada.');
    }

    // 3. Mostrar formulario "Nueva Contraseña" (Llega desde el correo)
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(['token' => $token, 'email' => $request->email]);
    }

    // 4. Guardar la nueva contraseña
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required'
        ], [
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'email.exists' => 'El correo electrónico no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'token.required' => 'El token de seguridad es necesario.'
            
        ]);

        // Verificar Token
        $checkToken = DB::table('password_reset_tokens')
            ->where(['email' => $request->email, 'token' => $request->token])
            ->first();

        if (!$checkToken) {
            return back()->withInput()->withErrors(['email' => 'El enlace es inválido o ha expirado. Por favor solicita uno nuevo.']);
        }

        // Actualizar Usuario
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        // Eliminar token usado
        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        return redirect()->route('login')->with('success', '¡Contraseña restablecida exitosamente! Ya puedes iniciar sesión.');
    }
}
