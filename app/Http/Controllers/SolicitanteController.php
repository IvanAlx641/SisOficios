<?php

namespace App\Http\Controllers;

use App\Models\Solicitante;
use App\Models\User; // Importante para la Policy
use Illuminate\Http\Request;
use Illuminate\View\View;

class SolicitanteController extends Controller
{
    // USAMOS EL MISMO CATÁLOGO MANUAL DE USUARIOS (Para que no marque error)
    protected $unidades = [
        1 => 'DIRECCIÓN GENERAL',
        2 => 'UNIDAD DE TRANSPARENCIA',
        3 => 'DIRECCIÓN DE TECNOLOGÍAS',
        4 => 'DEPARTAMENTO DE RECURSOS HUMANOS',
        5 => 'CONTRALORÍA INTERNA'
    ];

    protected $mensajes = [
        'nombre.required' => 'El campo nombre del solicitante es requerido.',
        'nombre.min' => 'El campo nombre del solicitante debe tener al menos 5 caracteres.',
        'cargo.required' => 'El campo cargo es requerido.',
        'unidad_administrativa_id.required' => 'El campo unidad administrativa es requerido.',
    ];

    public function __construct()
    {
        // Asegúrate de tener la SolicitantePolicy creada
        $this->authorizeResource(Solicitante::class, 'solicitante');
    }

    public function index(Request $request): View
    {
        $query = Solicitante::query();

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('inactivo')) {
            if ($request->inactivo == 'Inactivas') {
                $query->where('inactivo', 'X');
            } elseif ($request->inactivo == 'Activas') {
                $query->whereNull('inactivo');
            }
        }

        $solicitantes = $query->orderBy('id', 'desc')->paginate(25);
        $unidades = $this->unidades; // Usamos el array de arriba

        return view('solicitantes.index', compact('solicitantes', 'unidades', 'request'));
    }

    public function create(): View
    {
        $solicitante = new Solicitante();
        $unidades = $this->unidades;
        return view('solicitantes.form', compact('solicitante', 'unidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|min:5|max:190',
            'cargo' => 'required|string|max:190',
            'unidad_administrativa_id' => 'required',
        ], $this->mensajes);

        Solicitante::create([
            'nombre' => mb_convert_case($request->nombre, MB_CASE_TITLE, "UTF-8"),
            'cargo' => mb_strtoupper($request->cargo, "UTF-8"),
            'unidad_administrativa_id' => $request->unidad_administrativa_id,
            'dependencia_id' => 1, 
            'usuario_creacion_id' => auth()->id(),
        ]);

        return redirect()->route('solicitantes.index')->with('success', 'Solicitante registrado correctamente.');
    }
    /**
     * Muestra el formulario para editar un solicitante
     */
    public function edit(Solicitante $solicitante): View
    {
        // Pasamos el catálogo manual de unidades
        $unidades = $this->unidades;
        return view('solicitantes.form', compact('solicitante', 'unidades'));
    }

    /**
     * Actualiza el registro en la base de datos
     */
    public function update(Request $request, Solicitante $solicitante)
    {
        // Validamos con los mismos mensajes rojos de Usuarios
        $request->validate([
            'nombre' => 'required|string|min:5|max:190',
            'cargo' => 'required|string|max:190',
            'unidad_administrativa_id' => 'required',
        ], $this->mensajes);

        // Obtenemos todos los datos excepto tokens y el estatus
        $data = $request->except(['_token', '_method', 'inactivo']);
        
        // Formateamos nombre y cargo (Título y Mayúsculas)
        $data['nombre'] = mb_convert_case($request->nombre, MB_CASE_TITLE, "UTF-8");
        $data['cargo'] = mb_strtoupper($request->cargo, "UTF-8");

        // Lógica de inactivación (igual a Usuarios: si viene marcado es 'X', si no es null)
        $data['inactivo'] = $request->input('inactivo') === 'X' ? 'X' : null;
        
        // Auditoría de modificación
        $data['usuario_modificacion_id'] = auth()->id();

        $solicitante->update($data);

        return redirect()->route('solicitantes.index')
            ->with('success', 'Solicitante actualizado correctamente.');
    }

    /**
     * Desactivar solicitante (Acción rápida desde el index)
     */
    public function desactivar(Solicitante $solicitante)
    {
        $this->authorize('update', $solicitante);
        
        $solicitante->update([
            'inactivo' => 'X', 
            'usuario_modificacion_id' => auth()->id()
        ]);

        return redirect()->route('solicitantes.index')
            ->with('success', 'Solicitante desactivado correctamente.');
    }

    /**
     * Reactivar solicitante
     */
    public function reactivar(Solicitante $solicitante)
    {
        $this->authorize('update', $solicitante);
        
        $solicitante->update([
            'inactivo' => null, 
            'usuario_modificacion_id' => auth()->id()
        ]);

        return redirect()->route('solicitantes.index')
            ->with('success', 'Solicitante reactivado correctamente.');
    }

    /**
     * Eliminar registro físicamente
     */
    public function destroy(Solicitante $solicitante)
    {
        $this->authorize('delete', $solicitante);
        
        $solicitante->delete();

        return redirect()->route('solicitantes.index')
            ->with('success', 'Solicitante eliminado permanentemente.');
    }
    // ... resto de métodos (edit, update) siguiendo la misma lógica ...
}