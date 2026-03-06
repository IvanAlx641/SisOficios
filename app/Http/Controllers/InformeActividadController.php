<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Sistema;
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

        // 1. 🛡️ BLOQUEO ESTRICTO
        if ($rol === 'Capturista') {
            abort(403, 'No tienes permiso para acceder a los Informes de Actividades.');
        }

        // 2. CONSULTA BASE
        $query = Actividad::query();

        // 3. 🛡️ FILTROS POR ROL
        if ($rol === 'Responsable') {
            $query->where('responsable_id', $user->id);
        } elseif ($rol === 'Titular de área') {
            $query->whereHas('responsable', function($q) use ($user) {
                $q->where('unidad_administrativa_id', $user->unidad_administrativa_id);
            });
        }
        // Admin TI y Analista pasan directo y ven todo

        // 4. 📅 FILTRO DE FECHAS
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
            // AQUÍ ESTÁ LA CORRECCIÓN EXACTA DE TEXTO (Singular, tal como tu form)
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
        // GRÁFICA 2: BARRAS HORIZONTALES - SISTEMAS
        // ========================================================
        $dataSis = (clone $query)
            ->join('detalle_actividades', 'actividades.id', '=', 'detalle_actividades.actividad_id')
            ->select('actividades.sistema_id', DB::raw('count(detalle_actividades.id) as total'))
            ->groupBy('actividades.sistema_id')
            ->get();

        $sistemasNombres = Sistema::pluck('sigla_sistema', 'id');
        
        $categoriasSis = [];
        $seriesSis = [];
        foreach ($dataSis as $row) {
            $categoriasSis[] = mb_strtoupper($sistemasNombres[$row->sistema_id] ?? 'S/N');
            $seriesSis[] = $row->total;
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
            'request', 'rol',
            'labelsPieEstatus', 'seriesPieEstatus',
            'categoriasSis', 'seriesSis',
            'categoriasTiempo', 'seriesTiempo',
            'categoriasResp', 'seriesResp'
        ));
    }
}