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

    // Catálogo Temporal de Unidades (Esto luego vendrá de una tabla de BD)
    protected $unidades = [
        1 => 'DIRECCIÓN GENERAL',
        2 => 'UNIDAD DE TRANSPARENCIA',
        3 => 'DIRECCIÓN DE TECNOLOGÍAS',
        4 => 'DEPARTAMENTO DE RECURSOS HUMANOS',
        5 => 'CONTRALORÍA INTERNA'
    ];

    // Mensajes de error personalizados
    protected $mensajes = [
        'nombre.required' => 'El nombre del usuario es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 5 caracteres.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'Ingresa un correo electrónico válido.',
        'email.unique' => 'Este correo ya está registrado en el sistema.',
        'rol.required' => 'Debes seleccionar un rol.',
        'rol.in' => 'El rol seleccionado no es válido.',
        'unidad_administrativa_id.required' => 'La unidad administrativa (dependencia) es obligatoria.',
        'password.required' => 'La contraseña es obligatoria para usuarios nuevos.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
    ];

    public function __construct()
    {
        $this->authorizeResource(User::class, 'usuario');
    }

    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . mb_strtoupper($request->nombre) . '%');
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
        // 1. Validaciones
        $request->validate([
            'nombre' => 'required|string|min:5|max:190',
            'email' => 'required|email|unique:users,email',
            'rol' => 'required|in:' . implode(',', array_keys($this->roles)),
            'unidad_administrativa_id' => 'required', // AHORA ES OBLIGATORIO
        ], $this->mensajes); // Pasamos los mensajes personalizados

        // 2. Creación
        // Nota: Al crear manualmente, no pedimos contraseña en el form porque 
        // usaremos el botón de "Enviar Credenciales" después, o generamos una basura.
        User::create([
            'nombre' => mb_strtoupper($request->nombre),
            'email' => $request->email,
            'rol' => $request->rol,
            'unidad_administrativa_id' => $request->unidad_administrativa_id,
            'password' => Hash::make(Str::random(16)), // Contraseña temporal aleatoria
            'email_verified_at' => null, // Nace sin verificar
            'usuario_creacion_id' => auth()->id(),
        ]);

        return redirect()->route('usuario.index')
            ->with('success', 'Usuario registrado. Haz clic en el "Avioncito" para enviarle sus credenciales.');
    }

    public function edit(User $usuario): View
    {
        $roles = $this->roles;
        $unidades = $this->unidades;
        return view('usuarios.form', compact('usuario', 'roles', 'unidades'));
    }

    public function update(Request $request, User $usuario)
    {
        // 1. Validaciones
        $rules = [
            'nombre' => 'required|string|min:5|max:190',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'rol' => 'required|in:' . implode(',', array_keys($this->roles)),
            'unidad_administrativa_id' => 'required',
            // La contraseña es opcional (nullable) en edición. Solo si escribe algo, validamos.
            'password' => 'nullable|confirmed|min:8', 
        ];

        $request->validate($rules, $this->mensajes);

        // 2. Prepara datos
        $data = $request->except(['password', 'password_confirmation', '_token', '_method']);
        $data['nombre'] = mb_strtoupper($request->nombre);
        $data['usuario_modificacion_id'] = auth()->id();

        // 3. Lógica de Cambio de Contraseña Manual
        // Solo si el campo NO está vacío, la actualizamos.
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
            
            // Si el admin cambia la contraseña manualmente, asumimos que el usuario ya la conoce
            // Opcional: Podrías marcarlo verificado o no, depende de tu flujo.
            // Por seguridad, lo dejamos verificado para que no le pida cambiarla de nuevo inmediatamente.
            $data['email_verified_at'] = now(); 
        }

        $usuario->update($data);

        return redirect()->route('usuario.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // ... (El resto de métodos notificacion, desactivar, reactivar, destroy se mantienen igual) ...
    // Solo pego el resto para que el archivo esté completo si lo copias todo.
    
    public function notificacion(User $usuario)
    {
        $this->authorize('update', $usuario);

        if ($usuario->email_verified_at != null) {
            $token = Password::createToken($usuario);
            Mail::to($usuario->email)->send(new RecuperarContrasena($token, $usuario->email));
            return redirect()->route('usuario.index')->with('success', 'El usuario ya está activo. Se envió enlace de recuperación.');
        } else {
            $passwordTemporal = Str::random(9);
            $usuario->update([
                'password' => Hash::make($passwordTemporal),
                'email_verified_at' => null,
                'usuario_modificacion_id' => auth()->id()
            ]);

            try {
                Mail::to($usuario->email)->send(new CredencialesNuevoUsuario($usuario, $passwordTemporal));
                $mensaje = 'Credenciales temporales enviadas.';
                $tipo = 'success';
            } catch (\Exception $e) {
                $mensaje = 'Error al enviar: ' . $e->getMessage();
                $tipo = 'warning';
            }

            return redirect()->route('usuario.index')->with($tipo, $mensaje);
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