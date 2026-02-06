<?php

namespace App\Http\Controllers;

use App\Models\TipoRequerimiento;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TipoRequerimientoController extends Controller
{
    // Mensajes de error personalizados (Estándar visual)
    protected $mensajes = [
        'tipo_requerimiento.required' => 'El campo tipo de requerimiento es requerido.',
        'tipo_requerimiento.unique' => 'Este tipo de requerimiento ya se encuentra registrado.',
        'tipo_requerimiento.max' => 'El campo tipo de requerimiento no debe exceder de 255 caracteres.',
        'requerimiento_oficio.required_without' => 'Debes seleccionar al menos una opción (Oficios o Actividades).',
        'requerimiento_actividad.required_without' => 'Debes seleccionar al menos una opción (Oficios o Actividades).',
    ];

    public function __construct()
    {
        $this->authorizeResource(TipoRequerimiento::class, 'tiporequerimiento');
    }

    public function index(Request $request): View
    {
        $query = TipoRequerimiento::query();

        // Puedes usar 'with' si quisieras mostrar quién lo creó en la tabla
        // $query->with('creador', 'modificador'); 

        // 1. Filtro por Nombre
        if ($request->filled('tipo_requerimiento')) {
            $query->where('tipo_requerimiento', 'like', '%' . $request->tipo_requerimiento . '%');
        }

        // 2. Filtro por Estatus
        if ($request->filled('inactivo')) {
            if ($request->inactivo == 'Inactivos') {
                $query->where('inactivo', 'X');
            } elseif ($request->inactivo == 'Activos') {
                $query->whereNull('inactivo');
            }
        }

        $tiposrequerimientos = $query->orderBy('id', 'desc')->paginate(25);

        return view('tiposrequerimientos.index', compact('tiposrequerimientos', 'request'));
    }

    public function create(): View
    {
        $tiporequerimiento = new TipoRequerimiento();
        return view('tiposrequerimientos.form', compact('tiporequerimiento'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Validaciones
        $request->validate([
            'tipo_requerimiento' => 'bail|required|string|unique:ctipos_requerimientos,tipo_requerimiento|max:255',
            'requerimiento_oficio' => 'required_without:requerimiento_actividad',
            'requerimiento_actividad' => 'required_without:requerimiento_oficio',
        ], $this->mensajes);

        // Guardar
        TipoRequerimiento::create([
            'tipo_requerimiento' => mb_convert_case($request->tipo_requerimiento, MB_CASE_TITLE, "UTF-8"),
            'requerimiento_oficio' => $request->has('requerimiento_oficio') ? 'X' : null,
            'requerimiento_actividad' => $request->has('requerimiento_actividad') ? 'X' : null,
            'inactivo' => null, 
            'usuario_creacion_id' => auth()->id(),
            'fecha_creacion' => now()
        ]);

        return redirect()->route('tiporequerimiento.index')
            ->with('success', 'Tipo de requerimiento agregado exitosamente.');
    }

    public function edit(TipoRequerimiento $tiporequerimiento): View
    {
        return view('tiposrequerimientos.form', compact('tiporequerimiento'));
    }

    public function update(Request $request, TipoRequerimiento $tiporequerimiento): RedirectResponse
    {
        // Validaciones
        $request->validate([
            'tipo_requerimiento' => 'bail|required|string|max:255|unique:ctipos_requerimientos,tipo_requerimiento,' . $tiporequerimiento->id,
            'requerimiento_oficio' => 'required_without:requerimiento_actividad',
            'requerimiento_actividad' => 'required_without:requerimiento_oficio',
        ], $this->mensajes);

        // Actualizar
        $tiporequerimiento->update([
            'tipo_requerimiento' => mb_convert_case($request->tipo_requerimiento, MB_CASE_TITLE, "UTF-8"),
            'requerimiento_oficio' => $request->has('requerimiento_oficio') ? 'X' : null,
            'requerimiento_actividad' => $request->has('requerimiento_actividad') ? 'X' : null,
            'inactivo' => $request->input('inactivo') === 'X' ? 'X' : null,
            'usuario_modificacion_id' => auth()->id(),
            'fecha_modificacion' => now()
        ]);

        return redirect()->route('tiporequerimiento.index')
            ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy(TipoRequerimiento $tiporequerimiento): RedirectResponse
    {
        // PENDIENTE: Descomentar cuando existan los modelos Oficio y DetalleActividad
        /*
        if ($tiporequerimiento->oficios()->count() > 0 || $tiporequerimiento->detalleActividades()->count() > 0) {
            return redirect()->route('tiporequerimiento.index')
                ->with('error', 'El registro tiene información vinculada y no puede ser eliminado.');
        }
        */

        $tiporequerimiento->delete();

        return redirect()->route('tiporequerimiento.index')
            ->with('success', 'El registro ha sido eliminado.');
    }
} 