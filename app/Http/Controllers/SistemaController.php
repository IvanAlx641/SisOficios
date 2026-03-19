<?php

namespace App\Http\Controllers;

use App\Models\Sistema;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SistemaController extends Controller
{
    protected $mensajes = [
        'nombre_sistema.required' => 'El campo nombre del sistema es requerido.',
        'nombre_sistema.unique'   => 'Este nombre de sistema ya está registrado.',
        'sigla_sistema.required'  => 'El campo sigla del sistema es requerido.',
        'sigla_sistema.unique'    => 'Esta sigla ya está registrada.',
    ];

    public function __construct()
    {
        $this->authorizeResource(Sistema::class, 'sistema');
    }

    public function index(Request $request): View
    {
        $query = Sistema::query();

        if ($request->filled('nombre_sistema')) {
            $query->where('nombre_sistema', 'like', '%' . $request->nombre_sistema . '%');
        }

        if ($request->filled('sigla_sistema')) {
            $query->where('sigla_sistema', 'like', '%' . $request->sigla_sistema . '%');
        }

        if ($request->filled('inactivo')) {
            if ($request->inactivo == 'Inactivas') {
                $query->where('inactivo', 'X');
            } elseif ($request->inactivo == 'Activas') {
                $query->whereNull('inactivo');
            }
        }

        $sistemas = $query->orderBy('nombre_sistema', 'asc')->paginate(10);

        return view('sistemas.index', compact('sistemas', 'request'));
    }

    public function create(): View
    {
        $sistema = new Sistema();
        return view('sistemas.form', compact('sistema'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_sistema' => 'required|string|max:255|unique:csistemas,nombre_sistema',
            'sigla_sistema'  => 'required|string|max:255|unique:csistemas,sigla_sistema',
        ], $this->mensajes);

        Sistema::create([
            // Se guarda exactamente como viene en el request
            'nombre_sistema'      => $request->nombre_sistema,
            'sigla_sistema'       => $request->sigla_sistema,
            'usuario_creacion_id' => auth()->id(),
        ]);

        return redirect()->route('sistema.index')->with('success', 'Sistema registrado correctamente.');
    }


    public function edit(Sistema $sistema): View
    {
        return view('sistemas.form', compact('sistema'));
    }

    public function update(Request $request, Sistema $sistema)
    {
        $request->validate([
            'nombre_sistema' => 'required|string|max:255|unique:csistemas,nombre_sistema,' . $sistema->id,
            'sigla_sistema'  => 'required|string|max:255|unique:csistemas,sigla_sistema,' . $sistema->id,
        ], $this->mensajes);

        $data = $request->all();
        // Se sobreescribe usando el valor exacto del request, sin alteraciones de mayúsculas/minúsculas
        $data['nombre_sistema'] = $request->nombre_sistema;
        $data['sigla_sistema']  = $request->sigla_sistema;
        $data['inactivo']       = $request->input('inactivo') === 'X' ? 'X' : null;
        $data['usuario_modificacion_id'] = auth()->id();

        $sistema->update($data);

        return redirect()->route('sistema.index')->with('success', 'Sistema actualizado correctamente.');
    }

    public function destroy(Sistema $sistema)
    {
        // 1. Regla: Verificar si el sistema está vinculado a algún Oficio
        if ($sistema->oficios()->exists()) {
            return redirect()->route('sistema.index')->with('error', 'El sistema no se puede eliminar porque tiene Oficios registrados asociados a él.');
        }

        // 2. Regla: Verificar si el sistema tiene Actividades registradas
        if ($sistema->actividades()->exists()) {
            return redirect()->route('sistema.index')->with('error', 'El sistema no se puede eliminar porque tiene Actividades registradas asociadas a él.');
        }

        // 3. Si pasa las validaciones, procedemos a eliminar
        $sistema->delete();
        
        return redirect()->route('sistema.index')->with('success', 'Sistema eliminado correctamente del catálogo.');
    }
}