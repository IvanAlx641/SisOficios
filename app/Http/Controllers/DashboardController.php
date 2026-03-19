<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Actividad;
use App\Models\Sistema;
use App\Models\TipoRequerimiento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // 1. Agregamos (Request $request) para recibir el parámetro del formulario
    public function index(Request $request)
    {
        $user = auth()->user();
        $rol = $user->rol;

        // ========================================================
        // 1. CONTADORES DE ESTATUS (OFICIOS)
        // ========================================================
        $oficiosQuery = Oficio::query();

        // 🛡️ Filtros de Seguridad por Rol
        if ($rol === 'Titular de área') {
            $oficiosQuery->where('dirigido_id', $user->unidad_administrativa_id);
        } elseif ($rol === 'Responsable') {
            $oficiosQuery->whereHas('responsablesOficios', function($q) use ($user) {
                $q->where('responsable_id', $user->id);
            });
        } elseif ($rol === 'Capturista') {
            // El capturista solo ve los concluidos según requerimiento
            $oficiosQuery->where('estatus', 'Concluido');
        }

        // 📅 FILTRO POR AÑO PARA OFICIOS
        if ($request->filled('anio')) {
            $oficiosQuery->whereYear('fecha_recepcion', $request->anio);
        }

        $totales = [
            'Pendientes' => (clone $oficiosQuery)->where('estatus', 'Pendiente')->count(),
            'Turnados'   => (clone $oficiosQuery)->where('estatus', 'Turnado')->count(),
            'Atendidos'  => (clone $oficiosQuery)->where('estatus', 'Atendido')->count(),
            'Concluidos' => (clone $oficiosQuery)->where('estatus', 'Concluido')->count(),
        ];

        // ========================================================
        // 2. GRÁFICA 1: OFICIOS (Sistemas vs Tipos de Req)
        // ========================================================
        $oficiosData = (clone $oficiosQuery)
            ->select('sistema_id', 'tipo_requerimiento_id', DB::raw('count(*) as total'))
            ->groupBy('sistema_id', 'tipo_requerimiento_id')
            ->get();

        $sistemasOficiosIds = $oficiosData->pluck('sistema_id')->unique();
        $tiposReqIds = $oficiosData->pluck('tipo_requerimiento_id')->unique();

        $sistemasNombres = Sistema::whereIn('id', $sistemasOficiosIds)->pluck('sigla_sistema', 'id');
        $tiposReqNombres = TipoRequerimiento::whereIn('id', $tiposReqIds)->pluck('tipo_requerimiento', 'id');

        $categoriasChart1 = array_values($sistemasNombres->toArray()); // Eje Y (Sistemas)
        $seriesChart1 = [];

        foreach ($tiposReqNombres as $idTipo => $nombreTipo) {
            $data = [];
            foreach ($sistemasNombres as $idSis => $nombreSis) {
                $count = $oficiosData->where('sistema_id', $idSis)->where('tipo_requerimiento_id', $idTipo)->first()->total ?? 0;
                $data[] = $count;
            }
            $seriesChart1[] = [
                'name' => mb_strtoupper($nombreTipo),
                'data' => $data
            ];
        }

        // ========================================================
        // 3. GRÁFICA 2: ACTIVIDADES (Responsables vs Sistemas)
        // ========================================================
        $categoriasChart2 = [];
        $seriesChart2 = [];

        // 🛡️ El capturista NO debe ver gráficas de actividades
        if ($rol !== 'Capturista') { 
            $actividadesQuery = Actividad::query();

            // 🛡️ Filtros de Seguridad por Rol
            if ($rol === 'Titular de área') {
                $actividadesQuery->whereHas('responsable', function($q) use ($user) {
                    $q->where('unidad_administrativa_id', $user->unidad_administrativa_id);
                });
            } elseif ($rol === 'Responsable') {
                $actividadesQuery->where('responsable_id', $user->id);
            }

            // 📅 FILTRO POR AÑO PARA ACTIVIDADES
            if ($request->filled('anio')) {
                $actividadesQuery->whereYear('fecha_actividad', $request->anio);
            }

            $actividadesData = (clone $actividadesQuery)
                ->select('responsable_id', 'sistema_id', DB::raw('count(*) as total'))
                ->groupBy('responsable_id', 'sistema_id')
                ->get();

            $responsablesIds = $actividadesData->pluck('responsable_id')->unique();
            $sistemasActIds = $actividadesData->pluck('sistema_id')->unique();

            $responsablesNombres = User::whereIn('id', $responsablesIds)->pluck('nombre', 'id');
            $sistemasActNombres = Sistema::whereIn('id', $sistemasActIds)->pluck('sigla_sistema', 'id');

            // Formatear el nombre del responsable para que sea corto (Ej: "JUAN PÉREZ")
            $categoriasChart2Temp = [];
            foreach($responsablesNombres as $idResp => $nombreFull) {
                $partes = explode(' ', $nombreFull);
                $nombreCorto = mb_strtoupper($partes[0] . (isset($partes[1]) ? ' ' . $partes[1] : ''));
                $categoriasChart2Temp[$idResp] = $nombreCorto;
            }
            $categoriasChart2 = array_values($categoriasChart2Temp); // Eje Y (Responsables)

            foreach ($sistemasActNombres as $idSis => $nombreSis) {
                $data = [];
                foreach ($responsablesNombres as $idResp => $nombreFull) {
                    $count = $actividadesData->where('responsable_id', $idResp)->where('sistema_id', $idSis)->first()->total ?? 0;
                    $data[] = $count;
                }
                $seriesChart2[] = [
                    'name' => mb_strtoupper($nombreSis),
                    'data' => $data
                ];
            }
        }

        return view('dashboard', compact(
            'totales', 
            'categoriasChart1', 'seriesChart1', 
            'categoriasChart2', 'seriesChart2', 
            'rol',
            'request' // Lo pasamos compacto por si lo llegas a necesitar directamente en otra parte de la vista
        ));
    }
}