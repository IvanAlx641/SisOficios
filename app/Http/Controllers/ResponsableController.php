<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\ResponsableOficio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ResponsableController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        // 1. Obtener y validar Oficio de la Sesión
        // El controlador anterior permitía recibir por request o por sesión
        if ($request->has('oficio_id')) {
            // Asumo que el desencriptado es necesario si vienes de un link antiguo,
            // pero si mantienes el estándar, puedes pasar solo el ID.
            try {
                $oficio_id = decrypt($request->oficio_id);
            } catch (\Exception $e) {
                $oficio_id = $request->oficio_id; // Fallback por si no viene encriptado
            }
            session(['oficio_id' => $oficio_id]);
        }

        $oficioId = session('oficio_id');
        if (!$oficioId) {
            return redirect()->route('turno.index')->withErrors('Oficio no seleccionado.');
        }

        $oficio = Oficio::with(['areaDirigido', 'solicitantes', 'areaAsignada', 'sistema', 'tipoRequerimiento'])
            ->findOrFail($oficioId);

        // 2. Cargar Responsables Asignados
        $responsablesOficios = ResponsableOficio::with(['responsable.unidadAdministrativa'])
            ->where('oficio_id', $oficioId)
            ->get();

        // 3. Catálogo para el Modal (Usuarios Responsables)
        $queryResponsables = User::whereNull('inactivo')->where('rol', 'Responsable');
        
        // Regla: Si es Titular, solo ve los de su unidad
        if (auth()->user()->rol == 'Titular') {
            $queryResponsables->where('unidad_administrativa_id', auth()->user()->unidad_administrativa_id);
        }
        $responsables = $queryResponsables->orderBy('nombre', 'asc')->pluck('nombre', 'id');

        // 4. Estadística Amarilla
        $estadisticaRespuestas = ResponsableOficio::selectRaw('responsable_id, users.nombre, genera_respuesta, COUNT(*) as total')
            ->leftJoin('users', 'responsables_oficios.responsable_id', '=', 'users.id')
            ->whereNotNull('genera_respuesta')
            ->groupBy('responsable_id', 'users.nombre', 'genera_respuesta')
            ->get();

        return view('responsables.index', compact('oficio', 'responsablesOficios', 'responsables', 'estadisticaRespuestas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $oficioId = session('oficio_id');
        $generaRespuesta = $request->has('genera_respuesta') ? 'X' : null;

        $request->validate([
            'responsable_id' => 'required|integer|exists:users,id',
        ], ['responsable_id.required' => 'Seleccione un responsable.']);

        // A. Validar Duplicado en el Oficio
        if (ResponsableOficio::where('oficio_id', $oficioId)->where('responsable_id', $request->responsable_id)->exists()) {
            return redirect()->back()->with('error', 'Este responsable ya está asignado.');
        }

        // B. Validar Respuesta Única
        if ($generaRespuesta == 'X') {
            $yaHayRespondedor = ResponsableOficio::where('oficio_id', $oficioId)->whereNotNull('genera_respuesta')->exists();
            if ($yaHayRespondedor) {
                return redirect()->back()->with('error', 'Solo un responsable puede elaborar respuesta para este oficio.');
            }
        }

        ResponsableOficio::create([
            'oficio_id' => $oficioId,
            'responsable_id' => $request->responsable_id,
            'genera_respuesta' => $generaRespuesta,
            'fecha_creacion' => now(),
            'usuario_creacion_id' => auth()->id(),
        ]);

        return redirect()->route('responsable.index')->with('success', 'Responsable agregado exitosamente.');
    }

    public function destroy(ResponsableOficio $responsable): RedirectResponse
    {
        $responsable->delete();
        return redirect()->route('responsable.index')->with('success', 'Responsable eliminado.');
    }

    // Método AJAX para llenar el modal de Editar
    public function edit(ResponsableOficio $responsable)
    {
        return response()->json($responsable);
    }

    public function update(Request $request, ResponsableOficio $responsable): RedirectResponse
    {
        $oficioId = session('oficio_id');
        $generaRespuesta = $request->has('genera_respuesta') ? 'X' : null;

        // Validar si quiere ser el que responde, que no haya otro (excluyéndose a sí mismo)
        if ($generaRespuesta == 'X') {
            $yaHayRespondedor = ResponsableOficio::where('oficio_id', $oficioId)
                ->where('id', '!=', $responsable->id)
                ->whereNotNull('genera_respuesta')
                ->exists();

            if ($yaHayRespondedor) {
                return redirect()->back()->with('error', 'Solo un responsable puede elaborar respuesta. Desmarque al anterior primero.');
            }
        }

        $responsable->update([
            'responsable_id' => $request->responsable_id,
            'genera_respuesta' => $generaRespuesta,
            'fecha_modificacion' => now(),
            'usuario_modificacion_id' => auth()->id(),
        ]);

        return redirect()->route('responsable.index')->with('success', 'Responsable actualizado.');
    }
}