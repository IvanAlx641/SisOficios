<?php

namespace App\Http\Controllers;

// --- IMPORTS CORREGIDOS ---
use Illuminate\Support\Facades\Auth; // Facade correcta para Auth
use Illuminate\Support\Facades\Log;  // Facade correcta para Log
use Illuminate\Support\Facades\Mail; // Facade correcta para Mail (Faltaba esta)
use App\Mail\NotificarTurnoMailable; // Ruta correcta hacia tu Mailable
// --------------------------

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
        'sistema_id.required' => 'El campo sistema es requerido.',
        'sistema_id.exists' => 'El campo sistema es requerido.',
        'tipo_requerimiento_id.required' => 'El campo tipo de requerimiento es requerido.',
        'tipo_requerimiento_id.exists' => 'El campo tipo de requerimiento es requerido.',
        'estatus.required' => 'El campo estatus es requerido.',
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
        ])->has('solicitantes');
        
        if (auth()->user()->rol === 'Titular de área') {
            // Solo ve los oficios dirigidos a su propia área
            $query->where('dirigido_id', auth()->user()->unidad_administrativa_id);
        }

        if ($request->filled('numero_oficio')) {
            $query->where('numero_oficio', 'like', '%' . mb_strtoupper($request->numero_oficio) . '%');
        }
        
        // Asegurarnos de que no sea '0' (Todas las unidades)
        if ($request->filled('dirigido_id') && $request->dirigido_id != '0') {
            $query->where('dirigido_id', $request->dirigido_id);
        }

        if ($request->filled('fecha_recepcion')) {
            $query->whereDate('fecha_recepcion', '>=', $request->fecha_recepcion);
        }

        // Si el usuario ingresó la fecha de fin (al:)
        if ($request->filled('fecha_recepcion_fin')) {
            $query->whereDate('fecha_recepcion', '<=', $request->fecha_recepcion_fin);
        }
        
        // 1. Excluimos definitivamente los estatus que no queremos ver nunca
        $query->whereNotIn('estatus', ['Atendido', 'Concluido']);

        // 2. --- FILTRO DE ESTATUS ---
        if ($request->filled('estatus') && $request->estatus !== 'Todos') {
            // Ya no necesitamos el "if" especial para concluidos, 
            // simplemente filtramos por el estatus que el usuario seleccione.
            $query->where('estatus', $request->estatus);
        }
        $oficios = $query->orderBy('id', 'desc')->paginate(10);

        return view('turnos.index', compact('oficios', 'unidades', 'request'));
    }
    
   public function notificar($id)
    {
        try {
            // 1. Buscamos el oficio con TODAS las relaciones necesarias, agregando sistema y tipoRequerimiento
            $oficio = Oficio::with([
                'solicitantes', 
                'areaDirigido', 
                'responsablesOficios.responsable',
                'sistema',              // <--- Agregado
                'tipoRequerimiento'     // <--- Agregado
            ])->findOrFail($id);

            // 2. Obtenemos al emisor (quien dio clic al botón, usualmente el Titular)
            $emisor = Auth::user();

            $enviados = 0;

            // 3. Recorremos a los responsables y enviamos el correo INDIVIDUAL a cada uno
            foreach ($oficio->responsablesOficios as $ro) {
                // Verificamos que el responsable exista y tenga un correo válido
                if ($ro->responsable && $ro->responsable->email) {
                    
                    $destinatario = $ro->responsable; // Capturamos a quien va a recibir el correo

                    // Enviamos pasando los 3 datos: el oficio, quién lo turnó y a quién va dirigido
                    Mail::to($destinatario->email)->send(new NotificarTurnoMailable($oficio, $emisor, $destinatario));
                    
                    $enviados++;
                }
            }

            // 4. Verificamos si logramos enviar al menos un correo
            if ($enviados === 0) {
                return redirect()->back()->with('error', 'No se puede notificar: Los responsables asignados no cuentan con un correo electrónico registrado.');
            }

            // 5. Retornamos con éxito
            return redirect()->back()->with('success', 'Se ha enviado la notificación de turno por correo a los responsables asignados.');
            
        } catch (\Exception $e) {
            Log::error('Error al notificar turno: ' . $e->getMessage()); // Siempre es bueno loguear el error real
            // Regresamos el "Rayo X" por si hay fallas en la prueba
            return redirect()->back()->with('error', 'Ocurrió un error al intentar enviar la notificación: ' . $e->getMessage());
        }
    }
    
    public function edit(Oficio $turno): View
    {
        $oficio = $turno;

        $sistemas = DB::table('csistemas')
            ->select('id', 'sigla_sistema')
            ->whereNull('inactivo')
            ->orderBy('sigla_sistema', 'asc')
            ->pluck('sigla_sistema', 'id')
            ->toArray();

        $tiposRequerimientos = DB::table('ctipos_requerimientos')
            ->select('id', 'tipo_requerimiento')
            ->whereNull('inactivo')
            // 🚨 AQUÍ EL FILTRO MÁGICO PARA OFICIOS 🚨
            ->where('requerimiento_oficio', 'X') 
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