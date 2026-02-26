<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\DetalleActividad;
use App\Models\TipoRequerimiento;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class DetalleActividadController extends Controller
{
    public function index(Request $request): View
    {
        // Encriptación/Desencriptación de sesión
        if ($request->has('actividad_id')) {
            $actividad_id = decrypt($request->actividad_id);
            session(['actividad_id' => $actividad_id]);
        }    

        // Cargamos la cabecera de la actividad general
        $actividad = Actividad::with(['responsable', 'sistema'])->findOrFail(session('actividad_id'));

        // Catálogo de tipos de requerimiento
        $tipoRequerimiento = TipoRequerimiento::whereNotNull('requerimiento_actividad')
            ->whereNull('inactivo')
            ->orderBy('tipo_requerimiento', 'asc')
            ->pluck('tipo_requerimiento', 'id');    

        // Cargamos los detalles asociados a esta actividad
        $detalleactividades = DetalleActividad::with('tipoRequerimiento')
            ->where('actividad_id', session('actividad_id'))
            ->orderBy('id', 'asc')
            ->paginate(100);
       
        return view('detalleactividades.index', compact('actividad', 'detalleactividades', 'tipoRequerimiento', 'request'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tipo_requerimiento_id' => 'required',
            'descripcion_actividad' => 'required|string|max:4000',
            'estatus' => 'required|in:En proceso,Atendida',
        ], [
            'tipo_requerimiento_id.required' => 'Seleccione el tipo de requerimiento.',
            'descripcion_actividad.required' => 'La descripción de la actividad es obligatoria.',
            'estatus.required' => 'Seleccione un estatus.'
        ]);

        DetalleActividad::create([
            'actividad_id' => session('actividad_id'),
            'tipo_requerimiento_id' => $request->tipo_requerimiento_id,
            'descripcion_actividad' => $request->descripcion_actividad,
            'estatus' => $request->estatus,
            'fecha_creacion' => now(),
            'usuario_creacion_id' => auth()->id()
        ]);

        return Redirect::route('detalleactividad.index')->with('success', 'Detalle registrado exitosamente.');
    }

    // Retorna el JSON para llenar el modal de Editar usando AJAX
    public function edit(DetalleActividad $detalleactividad)
    {     
        return response()->json($detalleactividad);
    }

    public function update(Request $request, DetalleActividad $detalleactividad): RedirectResponse
    {
        $request->validate([
            'tipo_requerimiento_id' => 'required',
            'descripcion_actividad' => 'required|string|max:4000',
            'estatus' => 'required|in:En proceso,Atendida',
        ], [
            'tipo_requerimiento_id.required' => 'Seleccione el tipo de requerimiento.',
            'descripcion_actividad.required' => 'La descripción de la actividad es obligatoria.',
        ]);
       
        $detalleactividad->update([
            'tipo_requerimiento_id' => $request->tipo_requerimiento_id,
            'descripcion_actividad' => $request->descripcion_actividad,
            'estatus' => $request->estatus,
            'usuario_modificacion_id' => auth()->id(),
            'fecha_modificacion' => now()
        ]);

        return Redirect::route('detalleactividad.index')->with('success', 'Detalle modificado exitosamente.');
    }

    public function destroy(DetalleActividad $detalleactividad): RedirectResponse
    {
        $detalleactividad->delete();
        return Redirect::route('detalleactividad.index')->with('success', 'El registro ha sido eliminado exitosamente.');
    }
}