<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use App\Mail\CredencialesNuevoUsuario;
use App\Mail\RecuperarContrasena;

class UsuarioController extends Controller
{
    // Roles permitidos
    protected $roles = [
        'Administrador TI' => 'Administrador TI',
        'Titular de área' => 'Titular de área',
        'Capturista' => 'Capturista',
        'Responsable' => 'Responsable',
        'Analista' => 'Analista',
    ];

    // Catálogo Temporal
    protected $unidades = [
        1 => 'DIRECCIÓN GENERAL',
        2 => 'UNIDAD DE TRANSPARENCIA',
        3 => 'DIRECCIÓN DE TECNOLOGÍAS',
        4 => 'DEPARTAMENTO DE RECURSOS HUMANOS',
        5 => 'CONTRALORÍA INTERNA'
    ];

    // --- MENSAJES AJUSTADOS AL FORMATO DE LA IMAGEN ---
    protected $mensajes = [
        'nombre.required' => 'El campo nombre completo es requerido.',
        'nombre.min' => 'El campo nombre completo debe tener al menos 5 caracteres.',
        'email.required' => 'El campo correo electrónico es requerido.',
        'email.email' => 'El campo correo electrónico debe ser válido.',
        'email.unique' => 'Este correo electrónico ya está registrado.',
        'rol.required' => 'El campo rol es requerido.',
        'unidad_administrativa_id.required_unless' => 'El campo unidad administrativa es requerido a menos que rol se encuentre en Administrador TI.',
    ];

    public function __construct()
    {
        $this->authorizeResource(User::class, 'usuario');
    }

    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->filled('rol') && $request->rol != 'Todos') {
            $query->where('rol', $request->rol);
        }
        if ($request->filled('inactivo')) {
            if ($request->inactivo == 'Inactivas') {
                $query->where('inactivo', 'X');
            } elseif ($request->inactivo == 'Activas') {
                $query->whereNull('inactivo');
            }
        }

        $usuarios = $query->orderBy('id', 'desc')->paginate(25);
        return view('usuarios.index', compact('usuarios', 'request'));
    }

    public function create(): View
    {
        $usuario = new User();
        $roles = $this->roles;
        $unidades = $this->unidades;
        return view('usuarios.form', compact('usuario', 'roles', 'unidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|min:5|max:190',
            'email' => 'required|email|unique:users,email',
            'rol' => 'required|in:' . implode(',', array_keys($this->roles)),
            'unidad_administrativa_id' => 'required_unless:rol,Administrador TI', 
        ], $this->mensajes);

        $unidadId = ($request->rol === 'Administrador TI') ? null : $request->unidad_administrativa_id;

        User::create([
            'nombre' => mb_convert_case($request->nombre, MB_CASE_TITLE, "UTF-8"),
            'email' => $request->email,
            'rol' => $request->rol,
            'unidad_administrativa_id' => $unidadId,
            'password' => Hash::make(Str::random(16)), 
            'email_verified_at' => null,
            'usuario_creacion_id' => auth()->id(),
        ]);

        return redirect()->route('usuario.index')->with('success', 'Usuario registrado correctamente.');
    }

    public function edit(User $usuario): View
    {
        $roles = $this->roles;
        $unidades = $this->unidades;
        return view('usuarios.form', compact('usuario', 'roles', 'unidades'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|min:5|max:190',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'rol' => 'required|in:' . implode(',', array_keys($this->roles)),
            'unidad_administrativa_id' => 'required_unless:rol,Administrador TI',
        ], $this->mensajes);

        $data = $request->except(['_token', '_method', 'inactivo']);
        
        $data['nombre'] = mb_convert_case($request->nombre, MB_CASE_TITLE, "UTF-8");
        
        if ($request->rol === 'Administrador TI') {
            $data['unidad_administrativa_id'] = null;
        }

        $data['inactivo'] = $request->input('inactivo') === 'X' ? 'X' : null;
        $data['usuario_modificacion_id'] = auth()->id();

        $usuario->update($data);

        return redirect()->route('usuario.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function notificacion(User $usuario)
    {
        $this->authorize('update', $usuario);

        if ($usuario->email_verified_at != null) {
            $token = Password::createToken($usuario);
            Mail::to($usuario->email)->send(new RecuperarContrasena($token, $usuario->email));
            return redirect()->route('usuario.index')->with('success', 'Enlace de recuperación enviado.');
        } else {
            $passwordTemporal = Str::random(9);
            $usuario->update([
                'password' => Hash::make($passwordTemporal),
                'usuario_modificacion_id' => auth()->id()
            ]); 
            Mail::to($usuario->email)->send(new CredencialesNuevoUsuario($usuario, $passwordTemporal));
            return redirect()->route('usuario.index')->with('success', 'Credenciales temporales enviadas.');
        }
    }

    public function desactivar(User $usuario)
    {
        $this->authorize('update', $usuario);
        $usuario->update(['inactivo' => 'X', 'usuario_modificacion_id' => auth()->id()]);
        return redirect()->route('usuario.index')->with('success', 'Usuario desactivado.');
    }

    public function reactivar(User $usuario)
    {
        $this->authorize('update', $usuario);
        $usuario->update(['inactivo' => null, 'usuario_modificacion_id' => auth()->id()]);
        return redirect()->route('usuario.index')->with('success', 'Usuario reactivado.');
    }

    public function destroy(User $usuario)
    {
        $this->authorize('delete', $usuario);
        if ($usuario->id == auth()->id()) return redirect()->route('usuario.index')->with('error', 'No puedes eliminarte a ti mismo.');
        $usuario->delete();
        return redirect()->route('usuario.index')->with('success', 'Usuario eliminado.');
    }
}