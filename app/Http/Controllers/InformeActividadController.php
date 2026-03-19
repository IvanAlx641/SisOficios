<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Sistema;
use App\Models\TipoRequerimiento;
use App\Models\UnidadAdministrativa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InformeActividadController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $rol = $user->rol;

        // 1. CONSULTA BASE
        $query = Actividad::query();

        // 2. FILTROS POR ROL BASE
        if ($rol === 'Responsable') {
            $query->where('responsable_id', $user->id);
        } elseif ($rol === 'Titular de área') {
            $query->whereHas('responsable', function($q) use ($user) {
                $q->where('unidad_administrativa_id', $user->unidad_administrativa_id);
            });
        }
        // Admin TI, Administrador y Capturista pasan directo y ven todo por defecto

        // 3. LÓGICA DE LA DDL (Filtro por Unidad Administrativa)
        $unidades = collect();
        if (in_array($rol, ['Administrador', 'Administrador TI', 'Admin TI', 'Analista'])) {
            $unidades = UnidadAdministrativa::whereNull('inactivo')
                ->orderBy('nombre_unidad_administrativa')
                ->pluck('nombre_unidad_administrativa', 'id');
            
            if ($request->filled('unidad_administrativa_id')) {
                // Filtramos las actividades cuyos responsables pertenezcan a la unidad seleccionada
                $query->whereHas('responsable', function($q) use ($request) {
                    $q->where('unidad_administrativa_id', $request->unidad_administrativa_id);
                });
            }
        }

        // 4. FILTRO DE FECHAS
        if ($request->filled('fecha_inicial')) {
            $query->whereDate('fecha_actividad', '>=', $request->fecha_inicial);
        }
        if ($request->filled('fecha_final')) {
            $query->whereDate('fecha_actividad', '<=', $request->fecha_final);
        }

        $actividadesIds = (clone $query)->pluck('id');

        // ========================================================
        // GRÁFICA 1: PASTEL - "En proceso" vs "Atendida"
        // ========================================================
        $dataEstatus = DB::table('detalle_actividades')
            ->whereIn('actividad_id', $actividadesIds)
            ->whereIn('estatus', ['En proceso', 'Atendida']) 
            ->select('estatus', DB::raw('count(*) as total'))
            ->groupBy('estatus')
            ->get();

        $labelsPieEstatus = [];
        $seriesPieEstatus = [];
        foreach ($dataEstatus as $row) {
            $labelsPieEstatus[] = mb_strtoupper($row->estatus);
            $seriesPieEstatus[] = $row->total;
        }

        // ========================================================
        // GRÁFICA 2: BARRAS HORIZONTALES APILADAS - SISTEMAS VS TIPO REQ
        // ========================================================
        $dataSis = (clone $query)
            ->join('detalle_actividades', 'actividades.id', '=', 'detalle_actividades.actividad_id')
            // Corrección: El tipo_requerimiento_id se toma de detalle_actividades
            ->select('actividades.sistema_id', 'detalle_actividades.tipo_requerimiento_id', DB::raw('count(detalle_actividades.id) as total'))
            ->groupBy('actividades.sistema_id', 'detalle_actividades.tipo_requerimiento_id')
            ->get();

        $sistemasIds = $dataSis->pluck('sistema_id')->unique();
        $tiposReqIds = $dataSis->pluck('tipo_requerimiento_id')->unique();

        $sistemasNombres = Sistema::whereIn('id', $sistemasIds)->pluck('sigla_sistema', 'id');
        $tiposReqNombres = TipoRequerimiento::whereIn('id', $tiposReqIds)->pluck('tipo_requerimiento', 'id');

        $categoriasSis = array_values($sistemasNombres->toArray());
        $seriesSis = [];

        foreach ($tiposReqNombres as $idReq => $nomReq) {
            $data = [];
            foreach ($sistemasNombres as $idSis => $nomSis) {
                $val = $dataSis->where('sistema_id', $idSis)->where('tipo_requerimiento_id', $idReq)->first()->total ?? 0;
                $data[] = $val;
            }
            $seriesSis[] = ['name' => mb_strtoupper($nomReq), 'data' => $data];
        }

        // ========================================================
        // GRÁFICA 3: ÁREA - TENDENCIA POR FECHAS
        // ========================================================
        $dataTiempo = (clone $query)
            ->select(DB::raw('DATE(fecha_actividad) as fecha'), DB::raw('count(*) as total'))
            ->groupBy(DB::raw('DATE(fecha_actividad)'))
            ->orderBy('fecha', 'asc')
            ->get();

        $categoriasTiempo = [];
        $seriesTiempo = [];
        foreach ($dataTiempo as $row) {
            $categoriasTiempo[] = date('d/m/Y', strtotime($row->fecha));
            $seriesTiempo[] = $row->total;
        }

        // ========================================================
        // GRÁFICA 4: BARRAS HORIZONTALES - RESPONSABLES
        // ========================================================
        $dataResp = (clone $query)
            ->join('detalle_actividades', 'actividades.id', '=', 'detalle_actividades.actividad_id')
            ->select('actividades.responsable_id', DB::raw('count(detalle_actividades.id) as total'))
            ->groupBy('actividades.responsable_id')
            ->get();

        $usuariosNombres = User::pluck('nombre', 'id');
        
        $categoriasResp = [];
        $seriesResp = [];
        foreach ($dataResp as $row) {
            $nombreFull = $usuariosNombres[$row->responsable_id] ?? 'Usuario';
            $partes = explode(' ', $nombreFull);
            $nombreCorto = mb_strtoupper($partes[0] . (isset($partes[1]) ? ' ' . $partes[1] : ''));
            
            $categoriasResp[] = $nombreCorto;
            $seriesResp[] = $row->total;
        }

        return view('informes.actividades', compact(
            'request', 'rol', 'unidades',
            'labelsPieEstatus', 'seriesPieEstatus',
            'categoriasSis', 'seriesSis',
            'categoriasTiempo', 'seriesTiempo',
            'categoriasResp', 'seriesResp'
        ));
    }
}