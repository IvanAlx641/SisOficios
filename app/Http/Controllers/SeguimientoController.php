<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Seguimiento;
use App\Models\ResponsableOficio;
use App\Models\UnidadAdministrativa;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class SeguimientoController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Cargamos TODAS las relaciones (incluyendo oficiosVinculados para que no falle al recuperarlos)
        $query = Oficio::with([
            'areaDirigido', 
            'solicitantes', 
            'areaAsignada',
            'sistema',
            'tipoRequerimiento',
            'responsablesOficios.responsable',
            'responsablesOficios.seguimientos',
            'oficiosVinculados' // <--- SUPER IMPORTANTE PARA QUE LA VISTA LOS VEA
        ]);

        if ($request->filled('numero_oficio')) {
            $query->where('numero_oficio', 'like', '%' . $request->numero_oficio . '%');
        }

        if ($request->filled('dirigido_id')) {
            $query->where('dirigido_id', $request->dirigido_id);
        }

        if ($request->filled('fecha_recepcion')) {
            $query->whereDate('fecha_recepcion', $request->fecha_recepcion);
        }

        if ($request->filled('estatus') && $request->estatus !== 'Todos') {
            $query->where('estatus', $request->estatus);
        } else {
            $query->whereIn('estatus', ['Turnado', 'Concluido', 'En validación']); 
        }

        $oficios = $query->orderBy('id', 'desc')->paginate(50);
        
        $unidades = UnidadAdministrativa::whereNull('inactivo')
            ->orderBy('nombre_unidad_administrativa')
            ->pluck('nombre_unidad_administrativa', 'id');

        // Catálogo de oficios para el buscador
        $listaOficios = Oficio::orderBy('numero_oficio')->pluck('numero_oficio', 'id');

        return view('seguimientos.index', compact('oficios', 'request', 'unidades', 'listaOficios'));
    }

    public function storeAvance(Request $request, $responsableOficioId): RedirectResponse
    {
        $request->validate([
            'estatus' => 'required|in:Pendiente,En Desarrollo,En validación,Publicado',
            'observaciones' => 'required|string|max:2000',
        ]);

        Seguimiento::create([
            'responsable_oficio_id' => $responsableOficioId,
            'fecha_seguimiento' => now(),
            'estatus' => $request->estatus,
            'observaciones' => $request->observaciones,
            'fecha_creacion' => now(),
            'usuario_creacion_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Avance registrado en la línea de tiempo.');
    }

    public function concluir(Request $request, Oficio $oficio): RedirectResponse
    {
        // Actualizamos los datos generales
        $dataUpdate = [
            'estatus' => 'Concluido',
            'fecha_conclusion' => $request->fecha_conclusion,
            'propuesta_respuesta' => $request->propuesta_respuesta,
            'alcance_otro_oficio' => $request->has('alcance_otro_oficio') ? 'X' : null,
            'fecha_modificacion' => now(),
            'usuario_modificacion_id' => auth()->id(),
        ];

        // Guardamos archivo si existe
        if ($request->hasFile('soporte_documental')) {
            $path = $request->file('soporte_documental')->store('soportes', 'public');
            $dataUpdate['soporte_documental'] = $path;
        }

        $oficio->update($dataUpdate);

        // -------------------------------------------------------------
        // LA MAGIA A PRUEBA DE BALAS PARA LOS OFICIOS VINCULADOS
        // -------------------------------------------------------------
        if ($request->has('alcance_otro_oficio')) {
            
            // Forzamos a Laravel a leer el arreglo de los inputs ocultos.
            // Si por alguna razón no viene, le pasamos un arreglo vacío [] por defecto.
            $vinculados = $request->input('oficios_vinculados', []);
            
            // Sync sincroniza automáticamente la base de datos con este arreglo
            $oficio->oficiosVinculados()->sync($vinculados);
            
        } else {
            // Si el checkbox se desmarca, limpiamos todas las relaciones
            $oficio->oficiosVinculados()->detach();
        }

        return redirect()->back()->with('success', '¡El oficio ha sido concluido/actualizado exitosamente!');
    }
}