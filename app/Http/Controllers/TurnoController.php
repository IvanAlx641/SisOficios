<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\UnidadAdministrativa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;

class TurnoController extends Controller
{
    protected $mensajes = [
        'sistema_id.required' => 'El sistema es requerido.',
        'sistema_id.exists' => 'El sistema seleccionado no es válido.',
        'tipo_requerimiento_id.required' => 'El tipo de requerimiento es requerido.',
        'tipo_requerimiento_id.exists' => 'El tipo de requerimiento seleccionado no es válido.',
        'estatus.required' => 'Debe seleccionar un estatus.',
        'estatus.in' => 'El estatus seleccionado no es válido.',
        'observaciones_turno.max' => 'Las observaciones no pueden exceder los 2000 caracteres.',
    ];

    public function index(Request $request): View
    {
        $unidades = UnidadAdministrativa::orderBy('nombre_unidad_administrativa', 'asc')
            ->pluck('nombre_unidad_administrativa', 'id');

        $query = Oficio::with([
            'areaDirigido',
            'solicitantes',
            'sistema',
            'tipoRequerimiento',
            'responsablesOficios.responsable'
        ]);

        if ($request->filled('numero_oficio')) {
            $query->where('numero_oficio', 'like', '%' . mb_strtoupper($request->numero_oficio) . '%');
        }

        if ($request->filled('dirigido_id') && $request->dirigido_id != '0') { // Asegurarnos de que no sea '0' (Todas las unidades)
            $query->where('dirigido_id', $request->dirigido_id);
        }

        if ($request->filled('fecha_recepcion')) {
            $query->whereDate('fecha_recepcion', $request->fecha_recepcion);
        }

        // --- FILTRO DE ESTATUS MEJORADO ---
        if ($request->filled('estatus') && $request->estatus !== 'Todos') {
            // Manejar la dualidad Concluido/Atendido si es necesario en tu lógica
            if ($request->estatus == 'Concluido') {
                // Si en BD es Atendido o Concluido, los traemos ambos
                $query->whereIn('estatus', ['Concluido']);
            } else {
                $query->where('estatus', $request->estatus);
            }
        }

        $oficios = $query->orderBy('id', 'desc')->paginate(10);

        return view('turnos.index', compact('oficios', 'unidades', 'request'));
    }

    public function edit(Oficio $turno): View
    {
        $oficio = $turno;

        // Regresamos a la columna 'sigla_sistema' original
        $sistemas = DB::table('csistemas')
            ->select('id', 'sigla_sistema')
            ->whereNull('inactivo')
            ->orderBy('sigla_sistema', 'asc')
            ->pluck('sigla_sistema', 'id')
            ->toArray();

        // Regresamos a la columna 'tipo_requerimiento' original
        $tiposRequerimientos = DB::table('ctipos_requerimientos')
            ->select('id', 'tipo_requerimiento')
            ->whereNull('inactivo')
            ->orderBy('tipo_requerimiento', 'asc')
            ->pluck('tipo_requerimiento', 'id')
            ->toArray();

        return view('turnos.form', compact('oficio', 'sistemas', 'tiposRequerimientos'));
    }

    public function update(Request $request, Oficio $turno): RedirectResponse
    {
        $request->validate([
            'sistema_id' => 'required|integer|exists:csistemas,id',
            'tipo_requerimiento_id' => 'required|integer|exists:ctipos_requerimientos,id',
            'estatus' => 'required|in:Pendiente,Cancelado,Turnado,Atendido,Concluido,Eliminado',
            'observaciones_turno' => 'nullable|string|max:2000',
        ], $this->mensajes);

        // ASIGNACIÓN DIRECTA: Esto esquiva los bloqueos de seguridad del modelo
        $turno->sistema_id = $request->sistema_id;
        $turno->tipo_requerimiento_id = $request->tipo_requerimiento_id;
        $turno->estatus = $request->estatus;
        $turno->observaciones_turno = $request->observaciones_turno;

        // Lógica de fechas
        if ($request->estatus == 'Turnado') {
            $turno->fecha_turno = $turno->fecha_turno ?? now()->format('Y-m-d H:i:s');
            $turno->fecha_cancelacion = null;
        } elseif ($request->estatus == 'Cancelado') {
            $turno->fecha_turno = null;
            $turno->fecha_cancelacion = $turno->fecha_cancelacion ?? now()->format('Y-m-d H:i:s');
        }

        $turno->fecha_modificacion = now()->format('Y-m-d H:i:s');
        $turno->usuario_modificacion_id = auth()->id();

        // GUARDADO FORZADO
        $turno->save();

        // Redirección
        if ($request->estatus == 'Turnado') {
            return redirect()->route('responsable.index', ['oficio_id' => encrypt($turno->id)])
                ->with('success', 'Turno guardado. Ahora asigne los responsables.');
        }

        return redirect()->route('turno.index')
            ->with('success', 'Los datos del turno han sido actualizados exitosamente.');
    }
}
