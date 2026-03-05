<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Sistema;
use App\Models\TipoRequerimiento;
use App\Models\UnidadAdministrativa; // <-- Nuevo modelo agregado
use App\Models\Solicitante;          // <-- Nuevo modelo agregado
use Illuminate\Http\Request;
use Illuminate\View\View;

class BuscadorController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Cargamos las relaciones
        $query = Oficio::with([
            'areaDirigido',
            'solicitantes',
            'sistema',
            'tipoRequerimiento',
            'respuestasOficios'
        ]);
        if (auth()->user()->rol === 'Titular de área') {
            // El titular SOLO ve los oficios que fueron dirigidos a su unidad
            $query->where('dirigido_id', auth()->user()->unidad_administrativa_id);
        } 
        elseif (auth()->user()->rol === 'Responsable') {
            // El Responsable SOLO ve los oficios donde está asignado
            $query->whereHas('responsablesOficios', function ($q) {
                $q->where('responsable_id', auth()->id());
            });
        }

        // 2. Aplicamos los filtros
        // 2. Aplicamos los filtros
        if ($request->filled('numero_oficio')) {
            $query->where('numero_oficio', 'like', '%' . $request->numero_oficio . '%');
        }
        if ($request->filled('fecha_recepcion')) {
            $query->whereDate('fecha_recepcion', '>=', $request->fecha_recepcion);
        }
        if ($request->filled('fecha_recepcion_fin')) {
            $query->whereDate('fecha_recepcion', '<=', $request->fecha_recepcion_fin);
        }

        // Blindamos TODOS los selects contra el 0 y la palabra 'Todos'
        if ($request->filled('dirigido_id') && $request->dirigido_id != 0 && $request->dirigido_id !== 'Todos') {
            $query->where('dirigido_id', $request->dirigido_id);
        }

        if ($request->filled('solicitado_por_id') && $request->solicitado_por_id != 0 && $request->solicitado_por_id !== 'Todos') {
            $query->whereHas('solicitantes', function ($q) use ($request) {
                $q->whereKey($request->solicitado_por_id);
            });
        }

        if ($request->filled('estatus') && $request->estatus != 0 && $request->estatus !== 'Todos') {
            $query->where('estatus', $request->estatus);
        }

        if ($request->filled('sistema_id') && $request->sistema_id != 0 && $request->sistema_id !== 'Todos') {
            $query->where('sistema_id', $request->sistema_id);
        }

        if ($request->filled('tipo_requerimiento_id') && $request->tipo_requerimiento_id != 0 && $request->tipo_requerimiento_id !== 'Todos') {
            $query->where('tipo_requerimiento_id', $request->tipo_requerimiento_id);
        }

        if ($request->filled('descripcion')) {
            $query->where('descripción_oficio', 'like', '%' . $request->descripcion . '%');
        }

        // Corregimos el doble ;;
        $oficios = $query->orderBy('id', 'desc')->paginate(50)->withQueryString();

        // 3. Consultas para los menús desplegables (AHORA CON LOS CATÁLOGOS CORRECTOS)
        $unidades = UnidadAdministrativa::whereNull('inactivo')->orderBy('nombre_unidad_administrativa')->pluck('nombre_unidad_administrativa', 'id');
        $solicitantesList = Solicitante::orderBy('nombre')->pluck('nombre', 'id');
        $sistemas = Sistema::orderBy('nombre_sistema')->pluck('nombre_sistema', 'id');
        $tiposRequerimiento = TipoRequerimiento::whereNull('inactivo')->orderBy('tipo_requerimiento')->pluck('tipo_requerimiento', 'id');

        return view('buscador.index', compact('oficios', 'unidades', 'solicitantesList', 'sistemas', 'tiposRequerimiento', 'request'));
    }

    public function show($id): View
    {
        // Cargamos todas las relaciones profundas para la vista de detalles
        $oficio = Oficio::with([
            'areaDirigido',
            'areaAsignada',
            'solicitantes.unidadAdministrativa', // Relación para sacar la unidad del solicitante
            'sistema',
            'tipoRequerimiento',
            'responsablesOficios.responsable',
            'responsablesOficios.seguimientos',
            'respuestasOficios.dirigidoA',
            'respuestasOficios.firmadoPor'
        ])->findOrFail($id);

        return view('buscador.show', compact('oficio'));
    }
}
