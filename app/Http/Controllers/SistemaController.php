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
            'nombre_sistema'      => mb_convert_case($request->nombre_sistema, MB_CASE_TITLE, "UTF-8"),
            'sigla_sistema'       => mb_strtoupper($request->sigla_sistema, "UTF-8"),
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
        $data['nombre_sistema'] = mb_convert_case($request->nombre_sistema, MB_CASE_TITLE, "UTF-8");
        $data['sigla_sistema']  = mb_strtoupper($request->sigla_sistema, "UTF-8");
        $data['inactivo']       = $request->input('inactivo') === 'X' ? 'X' : null;
        $data['usuario_modificacion_id'] = auth()->id();

        $sistema->update($data);

        return redirect()->route('sistema.index')->with('success', 'Sistema actualizado correctamente.');
    }

    public function destroy(Sistema $sistema)
    {
       /* if ($sistema->oficios()->count() > 0 || $sistema->actividades()->count() > 0) {
            return redirect()->route('sistema.index')->with('error', 'El registro tiene información vinculada y no puede eliminarse.');
        }
*/
        $sistema->delete();
        return redirect()->route('sistema.index')->with('success', 'Sistema eliminado correctamente.');
    }
}