<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\RespuestaOficio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\UnidadAdministrativa;

class RespuestaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Oficio::with([
            'areaDirigido',
            'solicitantes',
            'sistema',
            'respuestasOficios.firmadoPor'
        ]);

        if ($request->filled('numero_oficio')) {
            $query->where('numero_oficio', 'like', '%' . $request->numero_oficio . '%');
        }

        if ($request->filled('fecha_recepcion')) {
            $query->whereDate('fecha_recepcion', '>=', $request->fecha_recepcion);
        }

        if ($request->filled('fecha_recepcion_fin')) {
            $query->whereDate('fecha_recepcion', '<=', $request->fecha_recepcion_fin);
        }

        // NUEVO: Filtro "Dirigido a"
        if ($request->filled('dirigido_id') && $request->dirigido_id !== 'Todos') {
            $query->where('dirigido_id', $request->dirigido_id);
        }

        if ($request->filled('estatus') && $request->estatus !== 'Todos') {
            $query->where('estatus', $request->estatus);
        } else {
            $query->whereIn('estatus', ['Concluido', 'Atendido']);
        }


        $oficios = $query->orderBy('id', 'desc')->paginate(50);

        // Catálogos para selects
        $usuarios = User::orderBy('nombre')->pluck('nombre', 'id');

        // NUEVO: Catálogo de unidades para el buscador
        $unidades = UnidadAdministrativa::whereNull('inactivo')
            ->orderBy('nombre_unidad_administrativa')
            ->pluck('nombre_unidad_administrativa', 'id');

        return view('respuestas.index', compact('oficios', 'request', 'usuarios', 'unidades'));
    }
    public function show(Oficio $oficio): View
    {
        // Cargamos todas las relaciones necesarias para la cabecera y la tabla
        $oficio->load([
            'solicitantes',
            'sistema',
            'respuestasOficios.firmadoPor',
            'respuestasOficios.dirigidoA'
        ]);

        $usuarios = User::orderBy('nombre')->pluck('nombre', 'id');

        return view('detallerespuestas.index', compact('oficio', 'usuarios'));
    }
    public function destroy(RespuestaOficio $respuesta)
    {
        // Guardamos el ID del oficio antes de borrar la respuesta para poder redireccionar
        $oficio_id = $respuesta->oficio_id;

        // Eliminamos la respuesta
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
            'dirigido_a_id' => 'required|exists:users,id',
            'url_oficio_respuesta' => 'nullable|url',
            'descripción_respuesta_oficio' => 'required|string',

        ];
        $messages = [
            'fecha_respuesta.required'              => 'La fecha de la respuesta es obligatoria.',
            'fecha_respuesta.date'                  => 'Ingrese una fecha válida para la respuesta.',
            'numero_oficio_respuesta.required'      => 'El número de oficio de respuesta es obligatorio.',
            'numero_oficio_respuesta.max'           => 'El número de oficio no puede tener más de 255 caracteres.',
            'dirigido_a_id.required'                => 'Por favor, seleccione a quién va dirigido el oficio.',
            'firmado_por_id.required'               => 'Por favor, seleccione quién firma el oficio.',
            'url_oficio_respuesta.url'              => 'La URL del documento no es válida (asegúrese de incluir http:// o https://).',
            'descripción_respuesta_oficio.required' => 'La descripción de la respuesta es obligatoria.',
        ];

        // 3. Ejecutamos la validación
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

        // Si agregar una respuesta debe cambiar el estatus del oficio a 'Atendido', descomenta lo siguiente:
        if ($oficio->estatus !== 'Atendido') {
            $oficio->update([
                'estatus' => 'Atendido',
                'fecha_modificacion' => now(),
                'usuario_modificacion_id' => auth()->id()
            ]);
        }
        if ($oficio->solicitud_conjunta === 'X') {
            // Si tiene 'X', viaja a la pantalla de historial/detalle de ese oficio
            return redirect()->route('detallerespuestas.index', $oficio->id)
                ->with('success', '¡Respuesta registrada! Al ser solicitud conjunta, puede gestionar el historial aquí.');
        }

        // Si NO es conjunta, regresa al index general
        return redirect()->route('respuestas.index')
            ->with('success', '¡Respuesta registrada exitosamente! El oficio ha sido Atendido.');
        return redirect()->back()->with('success', '¡Respuesta registrada exitosamente!');
    }
    public function update(Request $request, $id)
    {
        // 1. Validar los datos que vienen del formulario del modal
        $request->validate([
            'fecha_respuesta'              => 'required|date',
            'numero_oficio_respuesta'      => 'required|string|max:255',
            'dirigido_a_id'                => 'required|exists:users,id', // Asumiendo que va a la tabla de usuarios
            'firmado_por_id'               => 'required|exists:users,id',
            'url_oficio_respuesta'         => 'nullable|url',
            'descripción_respuesta_oficio' => 'required|string',
        ]);

        // 2. Buscar el registro en la base de datos
        // NOTA: Cambia "RespuestaOficio" por el nombre real de tu modelo si se llama distinto (ej. "Respuesta")
        $respuesta = \App\Models\RespuestaOficio::findOrFail($id);

        // 3. Actualizar la información
        $respuesta->update([
            'fecha_respuesta'              => $request->fecha_respuesta,
            'numero_oficio_respuesta'      => $request->numero_oficio_respuesta,
            'dirigido_a_id'                => $request->dirigido_a_id,
            'firmado_por_id'               => $request->firmado_por_id,
            'url_oficio_respuesta'         => $request->url_oficio_respuesta ?? '',
            'descripción_respuesta_oficio' => $request->descripción_respuesta_oficio,
        ]);

        // 4. Regresar a la vista anterior con un mensaje de éxito
        return back()->with('success', 'La respuesta se ha actualizado correctamente.');
    }
}
