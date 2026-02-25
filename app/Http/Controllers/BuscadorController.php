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

        // 2. Aplicamos los filtros
        if ($request->filled('numero_oficio')) {
            $query->where('numero_oficio', 'like', '%' . $request->numero_oficio . '%');
        }
        if ($request->filled('fecha_del') && $request->filled('fecha_al')) {
            $query->whereBetween('fecha_recepcion', [$request->fecha_del, $request->fecha_al]);
        }
        if ($request->filled('dirigido_id') && $request->dirigido_id !== 'Todos') {
            $query->where('dirigido_id', $request->dirigido_id);
        }
        if ($request->filled('solicitado_por_id') && $request->solicitado_por_id !== 'Todos') {
            $query->whereHas('solicitantes', function($q) use ($request) {
                // whereKey busca el ID correcto automáticamente, sin importar el nombre de la tabla
                $q->whereKey($request->solicitado_por_id); 
            });
        }
        if ($request->filled('estatus') && $request->estatus !== 'Todos') {
            $query->where('estatus', $request->estatus);
        }
        if ($request->filled('sistema_id') && $request->sistema_id !== 'Todos') {
            $query->where('sistema_id', $request->sistema_id);
        }
        if ($request->filled('tipo_requerimiento_id') && $request->tipo_requerimiento_id !== 'Todos') {
            $query->where('tipo_requerimiento_id', $request->tipo_requerimiento_id);
        }
        if ($request->filled('descripcion')) {
            $query->where('descripción_oficio', 'like', '%' . $request->descripcion . '%');
        }

        $oficios = $query->orderBy('id', 'desc')->paginate(50);

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