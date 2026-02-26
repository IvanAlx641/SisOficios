<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\User;
use App\Models\Sistema;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class ActividadController extends Controller
{
    public function index(Request $request): View
    {
        // AQUI ESTA LA MAGIA: Cargamos los detalles y sus tipos para las Cards
        $query = Actividad::with(['responsable', 'sistema', 'detalleActividades.tipoRequerimiento'])
            ->withCount('detalleActividades');

        // Filtros
        if ($request->filled('fecha_inicial')) {
            $query->whereDate('fecha_actividad', '>=', $request->fecha_inicial);
        }
        if ($request->filled('fecha_final')) {
            $query->whereDate('fecha_actividad', '<=', $request->fecha_final);
        }
        if ($request->filled('responsable_id') && $request->responsable_id !== 'Todos') {
            $query->where('responsable_id', $request->responsable_id);
        }
        if ($request->filled('sistema_id') && $request->sistema_id !== 'Todos') {
            $query->where('sistema_id', $request->sistema_id);
        }
        if ($request->filled('estatus') && $request->estatus !== 'Todas') {
            $query->whereHas('detalleActividades', function($q) use ($request) {
                $q->where('estatus', $request->estatus);
            });
        }

        $actividades = $query->orderBy('fecha_actividad', 'desc')->paginate(50);

        // Catálogos para los filtros
        $responsables = User::whereNull('inactivo')
            ->where('rol', 'Responsable')
            ->orderBy('nombre', 'asc')
            ->pluck('nombre', 'id');

        $sistemas = Sistema::whereNull('inactivo')
            ->orderBy('sigla_sistema', 'asc')
            ->pluck('sigla_sistema', 'id');

        return view('actividades.index', compact('actividades', 'request', 'responsables', 'sistemas'));
    }

    public function create(): View
    {
        $responsablesQuery = User::whereNull('inactivo')->where('rol', 'Responsable');
        
        $isResponsable = auth()->user()->rol == 'Responsable';
        if ($isResponsable) {
            $responsablesQuery->where('id', auth()->id());
        }
        $isTitular = auth()->user()->rol == 'Titular';
        if ($isTitular) {
            $responsablesQuery->where('unidad_administrativa_id', auth()->user()->unidad_administrativa_id);
        }

        $responsables = $responsablesQuery->orderBy('nombre', 'asc')->pluck('nombre', 'id');
        $sistemas = Sistema::whereNull('inactivo')->orderBy('sigla_sistema', 'asc')->pluck('sigla_sistema', 'id');
        $actividad = new Actividad();

        return view('actividades.form', compact('actividad', 'responsables', 'sistemas', 'isResponsable'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'fecha_actividad' => 'required|date',
            'responsable_id' => 'required',
            'sistema_id' => 'required',
        ], [
            'fecha_actividad.required' => 'La fecha de la actividad es obligatoria.',
            'responsable_id.required' => 'Debe seleccionar un responsable.',
            'sistema_id.required' => 'Debe seleccionar un sistema.'
        ]);

        $existe = Actividad::whereDate('fecha_actividad', $request->fecha_actividad)
            ->where('responsable_id', $request->responsable_id)
            ->where('sistema_id', $request->sistema_id)
            ->exists();

        if ($existe) {
            return back()->withInput()->withErrors(['fecha_actividad' => 'Ya existe una actividad con la misma fecha, responsable y sistema.']);
        }

        $actividad = Actividad::create([
            'fecha_actividad' => $request->fecha_actividad,
            'responsable_id' => $request->responsable_id,
            'sistema_id' => $request->sistema_id,
            'fecha_creacion' => now(),
            'usuario_creacion_id' => auth()->id()
        ]);

        return Redirect::route('detalleactividad.index', ['actividad_id' => encrypt($actividad->id)])->with('success', 'Actividad registrada exitosamente. Proceda a registrar los detalles.');
    }

    public function edit(Actividad $actividad): View
    {
        $responsablesQuery = User::whereNull('inactivo')->where('rol', 'Responsable');
        
        $isResponsable = auth()->user()->rol == 'Responsable';
        if ($isResponsable) {
            $responsablesQuery->where('id', auth()->id());
        }
        $isTitular = auth()->user()->rol == 'Titular';
        if ($isTitular) {
            $responsablesQuery->where('unidad_administrativa_id', auth()->user()->unidad_administrativa_id);
        }

        $responsables = $responsablesQuery->orderBy('nombre', 'asc')->pluck('nombre', 'id');
        $sistemas = Sistema::whereNull('inactivo')->orderBy('sigla_sistema', 'asc')->pluck('sigla_sistema', 'id');

        return view('actividades.form', compact('actividad', 'responsables', 'sistemas', 'isResponsable'));
    }

    public function update(Request $request, Actividad $actividad): RedirectResponse
    {
        $request->validate([
            'fecha_actividad' => 'required|date',
            'responsable_id' => 'required',
            'sistema_id' => 'required',
        ], [
            'fecha_actividad.required' => 'La fecha de la actividad es obligatoria.',
            'responsable_id.required' => 'Debe seleccionar un responsable.',
            'sistema_id.required' => 'Debe seleccionar un sistema.'
        ]);

        $existe = Actividad::whereDate('fecha_actividad', $request->fecha_actividad)
            ->where('responsable_id', $request->responsable_id)
            ->where('sistema_id', $request->sistema_id)
            ->where('id', '!=', $actividad->id)
            ->exists();

        if ($existe) {
            return back()->withInput()->withErrors(['fecha_actividad' => 'Ya existe otra actividad con la misma fecha, responsable y sistema.']);
        }

        $actividad->update([
            'fecha_actividad' => $request->fecha_actividad,
            'responsable_id' => $request->responsable_id,
            'sistema_id' => $request->sistema_id,
            'usuario_modificacion_id' => auth()->id(),
            'fecha_modificacion' => now()
        ]);

        return Redirect::route('actividad.index')->with('success', 'Datos generales modificados exitosamente.');
    }

    public function destroy(Actividad $actividad): RedirectResponse
    {
        if ($actividad->detalleActividades()->count() > 0) {
            return Redirect::route('actividad.index')->with('error', 'La actividad tiene detalles asociados, no se puede eliminar.');
        }

        $actividad->delete();
        return Redirect::route('actividad.index')->with('success', 'El registro ha sido eliminado exitosamente.');
    }
}