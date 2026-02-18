<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Solicitante;
use App\Models\SolicitanteOficio;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OficioSolicitanteController extends Controller
{
    public function index(): View
    {
        // 1. Recuperar Oficio de Sesión
        $oficioId = session('oficio_id');
        
        // Si no hay ID en sesión, abortamos o redirigimos (seguridad)
        if (!$oficioId) {
            // Puedes redirigir al index de oficios si prefieres
            return view('oficios.index')->withErrors('No se ha seleccionado un oficio.');
        }

        $oficio = Oficio::findOrFail($oficioId);
        
        // 2. Cargar datos
        $solicitantes = $oficio->solicitantes()->with('dependencia', 'unidadAdministrativa')->get();
        // Lista para el modal (SOLO ACTIVOS)
        $listaSolicitantes = Solicitante::whereNull('inactivo')->orderBy('nombre')->pluck('nombre', 'id');

        // Retornamos la vista del Tab 2
        return view('oficiosolicitantes.index', compact('oficio', 'solicitantes', 'listaSolicitantes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $oficioId = session('oficio_id');

        $request->validate([
            'solicitante_id' => 'required|integer|exists:csolicitantes,id',
        ], [
            'solicitante_id.required' => 'Debe seleccionar un solicitante.',
        ]);

        // Verificar duplicados
        $existe = SolicitanteOficio::where('oficio_id', $oficioId)
                    ->where('solicitante_id', $request->solicitante_id)->exists();

        if ($existe) {
            return redirect()->route('oficiosolicitante.index')
                ->with('error', 'El solicitante ya está asignado a este oficio.');
        }

        SolicitanteOficio::create([
            'oficio_id' => $oficioId,
            'solicitante_id' => $request->solicitante_id,
            'fecha_creacion' => now(),
            'usuario_creacion_id' => auth()->id(),
        ]);

        return redirect()->route('oficiosolicitante.index')
            ->with('success', 'Solicitante agregado correctamente.');
    }

    public function destroy($id): RedirectResponse
    {
        // Aquí $id es el ID del SOLICITANTE, borramos la relación usando el oficio de sesión
        $oficioId = session('oficio_id');

        SolicitanteOficio::where('oficio_id', $oficioId)
            ->where('solicitante_id', $id)
            ->delete();

        return redirect()->route('oficiosolicitante.index')
            ->with('success', 'Solicitante eliminado del oficio.');
    }
}