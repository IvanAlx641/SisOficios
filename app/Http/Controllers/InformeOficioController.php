<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\ResponsableOficio;
use App\Models\Sistema;
use App\Models\TipoRequerimiento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InformeOficioController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $rol = $user->rol;

        // 1. 🛡️ REGLA ESTRICTA: El Capturista NO tiene acceso aquí
        if ($rol === 'Capturista') {
            abort(403, 'No tienes permiso para acceder a los Informes de Oficios.');
        }

        // 2. INICIAR CONSULTA BASE DE OFICIOS
        $query = Oficio::query();

        // 3. 🛡️ FILTROS DE SEGURIDAD POR ROL
        if ($rol === 'Titular de área') {
            $query->where('dirigido_id', $user->unidad_administrativa_id);
        } elseif ($rol === 'Responsable') {
            $query->whereHas('responsablesOficios', function($q) use ($user) {
                $q->where('responsable_id', $user->id);
            });
        }
        // Admin TI y Analista pasan sin restricciones y ven todo

        // 4. 📅 FILTRO DE FECHAS (Basado en la fecha de recepción)
        if ($request->filled('fecha_inicial')) {
            $query->whereDate('fecha_recepcion', '>=', $request->fecha_inicial);
        }
        if ($request->filled('fecha_final')) {
            $query->whereDate('fecha_recepcion', '<=', $request->fecha_final);
        }

        // Extraer los IDs de los oficios válidos tras los filtros (Para usarlos en la gráfica de Responsables)
        $oficiosValidosIds = (clone $query)->pluck('id');

        // ========================================================
        // GRÁFICA 1: PASTEL - POR TIPO DE REQUERIMIENTO
        // ========================================================
        $dataTipoReq = (clone $query)
            ->select('tipo_requerimiento_id', DB::raw('count(*) as total'))
            ->groupBy('tipo_requerimiento_id')
            ->get();
        
        $reqNamesAll = TipoRequerimiento::pluck('tipo_requerimiento', 'id');
        $labelsPieReq = [];
        $seriesPieReq = [];
        
        foreach ($dataTipoReq as $row) {
            $labelsPieReq[] = mb_strtoupper($reqNamesAll[$row->tipo_requerimiento_id] ?? 'N/A');
            $seriesPieReq[] = $row->total;
        }

        // ========================================================
        // GRÁFICA 2: PASTEL - POR ESTATUS
        // ========================================================
        $dataEstatus = (clone $query)
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
        // GRÁFICA 3: BARRAS - SISTEMA VS TIPO REQUERIMIENTO
        // ========================================================
        $dataSisReq = (clone $query)
            ->select('sistema_id', 'tipo_requerimiento_id', DB::raw('count(*) as total'))
            ->groupBy('sistema_id', 'tipo_requerimiento_id')
            ->get();

        $sistemasIds = $dataSisReq->pluck('sistema_id')->unique();
        $tiposReqIdsBar1 = $dataSisReq->pluck('tipo_requerimiento_id')->unique();

        $sistemasNombres = Sistema::whereIn('id', $sistemasIds)->pluck('sigla_sistema', 'id');
        $tiposReqNombresBar1 = TipoRequerimiento::whereIn('id', $tiposReqIdsBar1)->pluck('tipo_requerimiento', 'id');

        $categoriasSis = array_values($sistemasNombres->toArray());
        $seriesSisReq = [];

        foreach ($tiposReqNombresBar1 as $idReq => $nomReq) {
            $data = [];
            foreach ($sistemasNombres as $idSis => $nomSis) {
                $val = $dataSisReq->where('sistema_id', $idSis)->where('tipo_requerimiento_id', $idReq)->first()->total ?? 0;
                $data[] = $val;
            }
            $seriesSisReq[] = ['name' => mb_strtoupper($nomReq), 'data' => $data];
        }

        // ========================================================
        // GRÁFICA 4: BARRAS - RESPONSABLE VS TIPO REQUERIMIENTO
        // ========================================================
        // Cruzamos la tabla pivote de responsables con oficios
        $dataRespReq = ResponsableOficio::whereIn('oficio_id', $oficiosValidosIds)
            ->join('oficios', 'responsables_oficios.oficio_id', '=', 'oficios.id')
            ->select('responsables_oficios.responsable_id', 'oficios.tipo_requerimiento_id', DB::raw('count(*) as total'))
            ->groupBy('responsables_oficios.responsable_id', 'oficios.tipo_requerimiento_id')
            ->get();

        $respIds = $dataRespReq->pluck('responsable_id')->unique();
        $tiposReqIdsBar2 = $dataRespReq->pluck('tipo_requerimiento_id')->unique();

        $respNombresFull = User::whereIn('id', $respIds)->pluck('nombre', 'id');
        $tiposReqNombresBar2 = TipoRequerimiento::whereIn('id', $tiposReqIdsBar2)->pluck('tipo_requerimiento', 'id');

        $categoriasRespDict = [];
        foreach ($respNombresFull as $id => $nomFull) {
            $partes = explode(' ', $nomFull);
            $nombreCorto = mb_strtoupper($partes[0] . (isset($partes[1]) ? ' ' . $partes[1] : ''));
            $categoriasRespDict[$id] = $nombreCorto;
        }
        
        $categoriasResp = array_values($categoriasRespDict);
        $seriesRespReq = [];

        foreach ($tiposReqNombresBar2 as $idReq => $nomReq) {
            $data = [];
            foreach ($categoriasRespDict as $idResp => $nomResp) {
                $val = $dataRespReq->where('responsable_id', $idResp)->where('tipo_requerimiento_id', $idReq)->first()->total ?? 0;
                $data[] = $val;
            }
            $seriesRespReq[] = ['name' => mb_strtoupper($nomReq), 'data' => $data];
        }

        return view('informes.oficios', compact(
            'request', 'rol',
            'labelsPieReq', 'seriesPieReq',
            'labelsPieEstatus', 'seriesPieEstatus',
            'categoriasSis', 'seriesSisReq',
            'categoriasResp', 'seriesRespReq'
        ));
    }
}