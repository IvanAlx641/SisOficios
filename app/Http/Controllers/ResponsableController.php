<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\ResponsableOficio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ResponsableController extends Controller
{
    public function index(Request $request): View
    {
        if ($request->has('oficio_id')) {
            session(['oficio_id' => decrypt($request->oficio_id)]);
        }

        $oficioId = session('oficio_id');

        $oficio = Oficio::with(['areaDirigido', 'solicitantes', 'sistema', 'tipoRequerimiento'])
            ->findOrFail($oficioId);

        // ==========================================
        // 🛡️ SEGURIDAD EN EL SELECT DE RESPONSABLES
        // ==========================================
        $responsablesQuery = User::select('id', 'nombre')
            ->whereNull('inactivo')
            ->where('rol', 'Responsable'); 
            
        // CORRECCIÓN: El rol se llama "Titular de área"
        if (auth()->user()->rol === 'Titular de área') {
            $responsablesQuery->where('unidad_administrativa_id', auth()->user()->unidad_administrativa_id);
        }

        $responsables = $responsablesQuery->orderBy('nombre', 'asc')
            ->pluck('nombre', 'id')
            ->toArray();

        // Los responsables que YA fueron asignados a este oficio
        $responsablesOficios = ResponsableOficio::with('responsable.unidadAdministrativa')
            ->where('oficio_id', $oficioId)
            ->get();

        // ==========================================
        // 📊 SEGURIDAD EN LA ESTADÍSTICA (CONTADORES)
        // ==========================================
        $queryEstadisticas = ResponsableOficio::selectRaw('responsables_oficios.responsable_id, users.nombre, responsables_oficios.genera_respuesta, COUNT(*) as total')
            ->leftJoin('users', 'responsables_oficios.responsable_id', '=', 'users.id')
            ->whereNotNull('responsables_oficios.genera_respuesta');

        // Si es Titular, filtramos las estadísticas para que solo cuente a los responsables de su área
        if (auth()->user()->rol === 'Titular de área') {
            $queryEstadisticas->where('users.unidad_administrativa_id', auth()->user()->unidad_administrativa_id);
        }

        $estadisticaRespuestas = $queryEstadisticas->groupBy('responsables_oficios.responsable_id', 'users.nombre', 'responsables_oficios.genera_respuesta')
            ->get();

        return view('responsables.index', compact('oficio', 'responsables', 'responsablesOficios', 'estadisticaRespuestas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validarResponsable($request)->validate();

        ResponsableOficio::create([
            'oficio_id' => session('oficio_id'),
            'responsable_id' => $request->responsable_id,
            'genera_respuesta' => $request->has('genera_respuesta') ? 'X' : null,
            'usuario_creacion_id' => auth()->id(),
            'fecha_creacion' => now(),
        ]);

        return redirect()->route('responsable.index')
            ->with('success', 'Responsable asignado exitosamente.');
    }

    public function edit(ResponsableOficio $responsable)
    {
        return response()->json($responsable);
    }

    public function update(Request $request, ResponsableOficio $responsable): RedirectResponse
    {
        $this->validarResponsable($request, $responsable->id)->validate();

        $responsable->update([
            'responsable_id' => $request->responsable_id,
            'genera_respuesta' => $request->has('genera_respuesta') ? 'X' : null,
            'usuario_modificacion_id' => auth()->id(),
            'fecha_modificacion' => now(),
        ]);

        return redirect()->route('responsable.index')
            ->with('success', 'Responsable actualizado exitosamente.');
    }

    public function destroy(ResponsableOficio $responsable): RedirectResponse
    {
        $responsable->delete();
        
        return redirect()->route('responsable.index')
            ->with('success', 'El responsable ha sido eliminado.');
    }

    /**
     * Validador personalizado para asegurar que solo haya un responsable de respuesta
     */
    protected function validarResponsable(Request $request, int $id = 0)
    {
        $oficio_id = session('oficio_id');
        
        $uniqueRule = Rule::unique('responsables_oficios', 'responsable_id')
            ->where('oficio_id', $oficio_id);
            
        if ($id > 0) {
            $uniqueRule->ignore($id);
        }

        $validator = Validator::make($request->all(), [
            'responsable_id' => ['required', 'integer', 'exists:users,id', $uniqueRule],
        ], [
            'responsable_id.required' => 'Debe seleccionar un responsable.',
            'responsable_id.unique' => 'Este usuario ya fue asignado a este oficio.',
        ]);

        $validator->after(function ($validator) use ($request, $id, $oficio_id) {
            if ($request->has('genera_respuesta')) {
                $query = ResponsableOficio::where('oficio_id', $oficio_id)
                    ->whereNotNull('genera_respuesta');
                    
                if ($id > 0) {
                    $query->where('id', '!=', $id);
                }
                
                if ($query->exists()) {
                    $validator->errors()->add('genera_respuesta', 'Solo un responsable puede elaborar la respuesta para este oficio.');
                }
            }
        });

        return $validator;
    }
}