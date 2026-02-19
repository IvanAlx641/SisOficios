<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Sistema;
use App\Models\TipoRequerimiento;
use App\Models\UnidadAdministrativa;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

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
        $query = Oficio::with(['areaDirigido', 'solicitantes', 'areaAsignada']);

        // FILTROS (Iguales a los del Index de Oficios)
        if ($request->filled('numero_oficio')) {
            $query->where('numero_oficio', 'like', '%' . mb_strtoupper($request->numero_oficio) . '%');
        }
        if ($request->filled('dirigido_id') && $request->dirigido_id != 0) {
            $query->where('dirigido_id', $request->dirigido_id);
        }
        if ($request->filled('fecha_recepcion')) {
            $query->whereDate('fecha_recepcion', $request->fecha_recepcion);
        }
        if ($request->filled('estatus') && $request->estatus !== 'Todos') {
            $query->where('estatus', $request->estatus);
        }

        $oficios = $query->orderBy('id', 'desc')->paginate(50);

        $unidades = UnidadAdministrativa::whereNull('inactivo')
            ->orderBy('nombre_unidad_administrativa')
            ->pluck('nombre_unidad_administrativa', 'id');

        return view('turnos.index', compact('oficios', 'unidades', 'request'));
    }

    public function edit(Oficio $turno): View
    {
        $oficio = $turno;

        // Guardamos en sesión para el Tab 2 (Responsables)
        session(['oficio_id' => $oficio->id]);

        $sistemas = Sistema::whereNull('inactivo')
            ->orderBy('sigla_sistema', 'asc')
            ->pluck('sigla_sistema', 'id');

        $tiposRequerimientos = TipoRequerimiento::whereNull('inactivo')
            ->orderBy('tipo_requerimiento', 'asc')
            ->pluck('tipo_requerimiento', 'id');

        return view('turnos.form', compact('oficio', 'sistemas', 'tiposRequerimientos'));
    }

    public function update(Request $request, Oficio $turno): RedirectResponse
    {
        // Asegúrate de incluir 'Atendido' en las opciones válidas (in:...)
        $request->validate([
            'sistema_id' => 'required|integer|exists:csistemas,id',
            'tipo_requerimiento_id' => 'required|integer|exists:ctipos_requerimientos,id',
            'estatus' => 'required|in:Pendiente,Cancelado,Turnado,Atendido,Concluido,Eliminado',
            'observaciones_turno' => 'nullable|string|max:2000',
        ], $this->mensajes);

        // Lógica de fechas según estatus
        $fechaTurno = $turno->fecha_turno;
        $fechaCancelacion = $turno->fecha_cancelacion;

        if ($request->estatus == 'Turnado') {
            $fechaTurno = now();
            $fechaCancelacion = null;
        } elseif ($request->estatus == 'Cancelado') {
            $fechaTurno = null;
            $fechaCancelacion = now();
        }

        $turno->update([
            'sistema_id' => $request->sistema_id,
            'tipo_requerimiento_id' => $request->tipo_requerimiento_id,
            'estatus' => $request->estatus,
            'observaciones_turno' => $request->observaciones_turno,
            'fecha_turno' => $fechaTurno,
            'fecha_cancelacion' => $fechaCancelacion,
            'fecha_modificacion' => now(),
            'usuario_modificacion_id' => auth()->id(),
        ]);

        return redirect()->route('responsable.index')
            ->with('success', 'Los datos del turno han sido actualizados exitosamente.');
    }
}
