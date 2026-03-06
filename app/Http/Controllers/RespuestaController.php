<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\RespuestaOficio;
use App\Models\User;
use App\Models\Solicitante; // <-- AGREGADO
use App\Models\UnidadAdministrativa;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RespuestaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Oficio::with([
            'areaDirigido',
            'solicitantes',
            'sistema',
            'respuestasOficios.firmadoPor'
        ])->has('solicitantes');;

        if ($request->filled('numero_oficio')) {
            $query->where('numero_oficio', 'like', '%' . $request->numero_oficio . '%');
        }

        if ($request->filled('fecha_recepcion')) {
            $query->whereDate('fecha_recepcion', '>=', $request->fecha_recepcion);
        }

        if ($request->filled('fecha_recepcion_fin')) {
            $query->whereDate('fecha_recepcion', '<=', $request->fecha_recepcion_fin);
        }

        if ($request->filled('dirigido_id') && $request->dirigido_id !=0) {
            $query->where('dirigido_id', $request->dirigido_id);
        }

        if ($request->filled('estatus') && $request->estatus !== 'Todos') {
            $query->where('estatus', $request->estatus);
        } else {
            $query->whereIn('estatus', ['Concluido', 'Atendido']);
        }


        $oficios = $query->orderBy('id', 'desc')
                 ->paginate(50)
                 ->withQueryString(); 

        // NUEVO: Catálogos separados para los selects
        $titulares = User::where('rol', 'Titular de área')->orderBy('nombre')->pluck('nombre', 'id');
        $solicitantesList = Solicitante::whereNull('inactivo')->orderBy('nombre')->pluck('nombre', 'id');

        $unidades = UnidadAdministrativa::whereNull('inactivo')
            ->orderBy('nombre_unidad_administrativa')
            ->pluck('nombre_unidad_administrativa', 'id');

        return view('respuestas.index', compact('oficios', 'request', 'titulares', 'solicitantesList', 'unidades'));
    }

    public function show(Oficio $oficio): View
    {
        $oficio->load([
            'solicitantes',
            'sistema',
            'respuestasOficios.firmadoPor',
            'respuestasOficios.dirigidoA'
        ]);

        // NUEVO: Catálogos separados
        $titulares = User::where('rol', 'Titular de área')->orderBy('nombre')->pluck('nombre', 'id');
        $solicitantesList = Solicitante::whereNull('inactivo')->orderBy('nombre')->pluck('nombre', 'id');

        return view('detallerespuestas.index', compact('oficio', 'titulares', 'solicitantesList'));
    }

    public function destroy(RespuestaOficio $respuesta)
    {
        $oficio_id = $respuesta->oficio_id;
        $respuesta->delete();

        $oficio = Oficio::find($oficio_id);
        if ($oficio->respuestasOficios()->count() === 0) {
            $oficio->update(['estatus' => 'Concluido']);
        }

        return redirect()->back()->with('success', 'La respuesta ha sido eliminada correctamente.');
    }

    public function store(Request $request, Oficio $oficio): RedirectResponse
    {
        $rules = [
            'fecha_respuesta' => 'required|date',
            'numero_oficio_respuesta' => 'required|string|max:255',
            'firmado_por_id' => 'required|exists:users,id',
            'dirigido_a_id' => 'required|exists:csolicitantes,id', // <-- CAMBIO A csolicitantes (o la tabla que corresponda)
            'url_oficio_respuesta' => 'nullable|url',
            'descripción_respuesta_oficio' => 'required|string',
        ];
        $messages = [
            'fecha_respuesta.required'              => 'El campo fecha de la respuesta es obligatoria.',
            'fecha_respuesta.date'                  => 'Ingrese una fecha válida para la respuesta.',
            'numero_oficio_respuesta.required'      => 'El campo número de oficio de respuesta es obligatorio.',
            'numero_oficio_respuesta.max'           => 'El número de oficio no puede tener más de 255 caracteres.',
            'dirigido_a_id.required'                => 'El campo dirigido a es obligatorio',
            'firmado_por_id.required'               => 'El campo firmado por es obligatorio',
            'url_oficio_respuesta.url'              => 'La URL del documento no es válida (asegúrese de incluir http:// o https://).',
            'descripción_respuesta_oficio.required' => 'El campo descripción de la respuesta es obligatoria.',
        ];

        $request->validate($rules, $messages);

        RespuestaOficio::create([
            'oficio_id' => $oficio->id,
            'fecha_respuesta' => $request->fecha_respuesta,
            'numero_oficio_respuesta' => $request->numero_oficio_respuesta,
            'firmado_por_id' => $request->firmado_por_id,
            'dirigido_a_id' => $request->dirigido_a_id,
            'url_oficio_respuesta' => $request->url_oficio_respuesta ?? '',
            'descripción_respuesta_oficio' => $request->descripción_respuesta_oficio,
            'fecha_creacion' => now(),
            'usuario_creacion_id' => auth()->id(),
        ]);

        if ($oficio->estatus !== 'Atendido') {
            $oficio->update([
                'estatus' => 'Atendido',
                'fecha_modificacion' => now(),
                'usuario_modificacion_id' => auth()->id()
            ]);
        }
        
        if ($oficio->solicitud_conjunta === 'X') {
            return redirect()->route('detallerespuestas.index', $oficio->id)
                ->with('success', '¡Respuesta registrada! Al ser solicitud conjunta, puede gestionar el historial aquí.');
        }

        return redirect()->route('respuestas.index')
            ->with('success', '¡Respuesta registrada exitosamente! El oficio ha sido Atendido.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha_respuesta'              => 'required|date',
            'numero_oficio_respuesta'      => 'required|string|max:255',
            'dirigido_a_id'                => 'required|exists:csolicitantes,id', // <-- CAMBIO A csolicitantes
            'firmado_por_id'               => 'required|exists:users,id',
            'url_oficio_respuesta'         => 'nullable|url',
            'descripción_respuesta_oficio' => 'required|string',
        ]);

        $respuesta = \App\Models\RespuestaOficio::findOrFail($id);

        $respuesta->update([
            'fecha_respuesta'              => $request->fecha_respuesta,
            'numero_oficio_respuesta'      => $request->numero_oficio_respuesta,
            'dirigido_a_id'                => $request->dirigido_a_id,
            'firmado_por_id'               => $request->firmado_por_id,
            'url_oficio_respuesta'         => $request->url_oficio_respuesta ?? '',
            'descripción_respuesta_oficio' => $request->descripción_respuesta_oficio,
        ]);

        return back()->with('success', 'La respuesta se ha actualizado correctamente.');
    }
}