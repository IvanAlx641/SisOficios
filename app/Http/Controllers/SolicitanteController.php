<?php

namespace App\Http\Controllers;

use App\Models\Solicitante;
use App\Models\Dependencia;
use App\Models\UnidadAdministrativa;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class SolicitanteController extends Controller
{
    protected $mensajes = [
        'nombre.required' => 'El nombre del solicitante es obligatorio.',
        'dependencia_id.required' => 'Debes seleccionar una dependencia.',
        'unidad_administrativa_id.required' => 'Debes seleccionar una unidad administrativa.',
        'cargo.required' => 'El cargo o puesto es obligatorio.',
    ];

    public function __construct()
    {
        $this->authorizeResource(Solicitante::class, 'solicitante');
    }

    public function index(Request $request): View
    {
        // Carga para el filtro
        $dependencias = Dependencia::whereNull('inactivo')
            ->orderBy('nombre_dependencia')
            ->pluck('nombre_dependencia', 'id');

        // Query principal con relaciones
        $query = Solicitante::with(['dependencia', 'unidadAdministrativa']);

        // Filtros
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('dependencia_id') && $request->dependencia_id != 'Todas') {
            $query->where('dependencia_id', $request->dependencia_id);
        }

        if ($request->filled('inactivo')) {
            if ($request->inactivo == 'Inactivas') {
                $query->where('inactivo', 'X');
            } elseif ($request->inactivo == 'Activas') {
                $query->whereNull('inactivo');
            }
        }

        $solicitantes = $query->orderBy('id', 'desc')->paginate(50);

        return view('solicitantes.index', compact('solicitantes', 'dependencias', 'request'));
    }

    public function create(): View
    {
        $solicitante = new Solicitante();
        
        $dependencias = Dependencia::whereNull('inactivo')
            ->orderBy('nombre_dependencia')
            ->pluck('nombre_dependencia', 'id');
            
        return view('solicitantes.form', compact('solicitante', 'dependencias'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'bail|required|string|max:190',
            'dependencia_id' => 'bail|required|integer|exists:cdependencias,id',
            'unidad_administrativa_id' => 'bail|required|integer|exists:cunidades_administrativas,id',
            'cargo' => 'bail|required|string|max:150',
        ], $this->mensajes);

        Solicitante::create([
            'nombre' => mb_convert_case($request->nombre, MB_CASE_TITLE, "UTF-8"),
            'dependencia_id' => $request->dependencia_id,
            'unidad_administrativa_id' => $request->unidad_administrativa_id,
            'cargo' => mb_strtoupper($request->cargo),
            'inactivo' => null,
            'fecha_creacion' => now(),
            'usuario_creacion_id' => auth()->id(),
        ]);

        return redirect()->route('solicitante.index')
            ->with('success', 'Solicitante registrado exitosamente.');
    }

    public function edit(Solicitante $solicitante): View
    {
        $dependencias = Dependencia::whereNull('inactivo')
            ->orderBy('nombre_dependencia')
            ->pluck('nombre_dependencia', 'id');
            
        // Cargamos las unidades de esa dependencia para que el select aparezca lleno
        $unidades = UnidadAdministrativa::where('dependencia_id', $solicitante->dependencia_id)
            ->whereNull('inactivo')
            ->orderBy('nombre_unidad_administrativa')
            ->pluck('nombre_unidad_administrativa', 'id');

        return view('solicitantes.form', compact('solicitante', 'dependencias', 'unidades'));
    }

    public function update(Request $request, Solicitante $solicitante): RedirectResponse
    {
        $request->validate([
            'nombre' => 'bail|required|string|max:190',
            'dependencia_id' => 'bail|required|integer|exists:cdependencias,id',
            'unidad_administrativa_id' => 'bail|required|integer|exists:cunidades_administrativas,id',
            'cargo' => 'bail|required|string|max:150',
        ], $this->mensajes);

        $solicitante->update([
            'nombre' => mb_convert_case($request->nombre, MB_CASE_TITLE, "UTF-8"),
            'dependencia_id' => $request->dependencia_id,
            'unidad_administrativa_id' => $request->unidad_administrativa_id,
            'cargo' => mb_strtoupper($request->cargo),
            'inactivo' => $request->input('inactivo') === 'X' ? 'X' : null,
            'fecha_modificacion' => now(),
            'usuario_modificacion_id' => auth()->id(),
        ]);

        return redirect()->route('solicitante.index')
            ->with('success', 'Solicitante actualizado correctamente.');
    }

    public function destroy(Solicitante $solicitante): RedirectResponse
    {
        // 1. VALIDACIÓN DE INTEGRIDAD
        // Si el solicitante tiene oficios vinculados, NO se elimina.
        if ($solicitante->oficios()->exists()) {
            return redirect()->route('solicitante.index')
                ->with('error', 'El solicitante tiene oficios asignados.');
        }

        // 2. Si está limpio, procedemos
        $solicitante->delete();

        return redirect()->route('solicitante.index')
            ->with('success', 'El registro ha sido eliminado exitosamente.');
    }

    // Método AJAX para cargar unidades dinámicamente
    public function getUnidades($dependencia_id): JsonResponse
    {
        $unidades = UnidadAdministrativa::where('dependencia_id', $dependencia_id)
            ->whereNull('inactivo')
            ->orderBy('orden', 'asc') // Ordenar por campo 'orden'
            ->get(['id', 'nombre_unidad_administrativa']);

        return response()->json($unidades);
    }
    
}