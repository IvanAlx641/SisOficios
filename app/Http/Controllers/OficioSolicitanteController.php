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
    public function index(): View|RedirectResponse
    {
        // 1. Recuperar Oficio de Sesión
        $oficioId = session('oficio_id');
        
        if (!$oficioId) {
            return redirect()->route('oficio.index')->withErrors('No se ha seleccionado un oficio en curso.');
        }

        $oficio = Oficio::findOrFail($oficioId);
        
        // 2. Cargar datos
        $solicitantes = $oficio->solicitantes()->with('dependencia', 'unidadAdministrativa')->get();
        $listaSolicitantes = Solicitante::whereNull('inactivo')->orderBy('nombre')->pluck('nombre', 'id');

        // 3. Lógica para saber si puede agregar más (Solo 1 si NO es conjunta)
        $puedeAgregar = true;
        if ($oficio->solicitud_conjunta !== 'X' && $solicitantes->count() >= 1) {
            $puedeAgregar = false;
        }

        return view('oficiosolicitantes.index', compact('oficio', 'solicitantes', 'listaSolicitantes', 'puedeAgregar'));
    }

    public function store(Request $request): RedirectResponse
    {
        $oficioId = session('oficio_id');
        $oficio = Oficio::findOrFail($oficioId);

        $request->validate([
            'solicitante_id' => 'required|integer|exists:csolicitantes,id',
        ], [
            'solicitante_id.required' => 'Debe seleccionar un solicitante.',
        ]);

        // CANDADO: Verificar si excede el límite según el tipo de solicitud
        $cantidadActual = SolicitanteOficio::where('oficio_id', $oficioId)->count();
        if ($oficio->solicitud_conjunta !== 'X' && $cantidadActual >= 1) {
            return redirect()->route('oficiosolicitante.index')
                ->with('error', 'Este oficio no es de solicitud conjunta. Solo se permite agregar un solicitante.');
        }

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
        $oficioId = session('oficio_id');

        SolicitanteOficio::where('oficio_id', $oficioId)
            ->where('solicitante_id', $id)
            ->delete();

        return redirect()->route('oficiosolicitante.index')
            ->with('success', 'Solicitante eliminado del oficio.');
    }

    // NUEVO MÉTODO: Para validar que no se quede vacío al terminar
    public function finalizar(): RedirectResponse
    {
        $oficioId = session('oficio_id');
        
        if (!$oficioId) {
            return redirect()->route('oficio.index');
        }

        $cantidad = SolicitanteOficio::where('oficio_id', $oficioId)->count();

        // Validamos que haya al menos uno
        if ($cantidad === 0) {
            return redirect()->route('oficiosolicitante.index')
                ->with('error', 'Debe agregar al menos un solicitante para guardar y finalizar el registro del oficio.');
        }

        // Limpiamos la sesión para dar por terminado el registro
        session()->forget('oficio_id');

        return redirect()->route('oficio.index')->with('success', 'Oficio completado y registrado exitosamente.');
    }
}