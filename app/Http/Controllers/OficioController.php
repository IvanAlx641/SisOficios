<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\UnidadAdministrativa;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth; // Facade correcta para Auth
use Illuminate\Support\Facades\Log;  // Facade correcta para Log
use Illuminate\Support\Facades\Mail; // Facade correcta para Mail (Faltaba esta)
use App\Mail\NotificarRegistroMailable; // 
class OficioController extends Controller
{
    // Mensajes de error en español
    protected $mensajes = [
        'required' => 'El campo :attribute es obligatorio.',
        'unique' => 'Este número de oficio ya existe.',
        'date' => 'Ingrese una fecha válida.',
        'url' => 'Ingrese una URL válida (http(s)://...).',
        'integer' => 'Seleccione una opción válida.',
    ];
    //Mensaje solicitante
    public function index(Request $request): View|RedirectResponse
    {
        // --- CANDADO: Evitar salir sin solicitantes ---
        if (session()->has('oficio_id')) {
            $oficioIncompleto = Oficio::find(session('oficio_id'));

            // Si el oficio existe en sesión pero no tiene solicitantes, lo regresamos a la pestaña 2
            if ($oficioIncompleto && $oficioIncompleto->solicitantes()->count() === 0) {
                return redirect()->route('oficiosolicitante.index')
                    ->with('error', 'Acción denegada: Debe agregar al menos un solicitante antes de salir al listado principal.');
            }

            // Si sí tiene solicitantes y entró al index por el menú, damos el registro por terminado y limpiamos la sesión
            session()->forget('oficio_id');
        }

        $query = Oficio::with(['areaDirigido', 'solicitantes', 'areaAsignada']);

        // --- FILTROS ---
        if ($request->filled('numero_oficio')) {
            $query->where('numero_oficio', 'like', '%' . $request->numero_oficio . '%');
        }

        if ($request->filled('dirigido_id') && $request->dirigido_id != 0) {
            $query->where('dirigido_id', $request->dirigido_id);
        }

        if ($request->filled('fecha_recepcion')) {
            $query->whereDate('fecha_recepcion', '>=', $request->fecha_recepcion);
        }

        // Si el usuario ingresó la fecha de fin (al:)
        if ($request->filled('fecha_recepcion_fin')) {
            $query->whereDate('fecha_recepcion', '<=', $request->fecha_recepcion_fin);
        }
        $query->where('estatus', '!=', 'Atendido');

        // --- FILTRO DE ESTATUS MEJORADO ---
        if ($request->filled('estatus') && $request->estatus !== 'Todos') {
            // Si el usuario busca un estatus específico, respetamos su búsqueda
            if ($request->estatus == 'Concluido') {
                $query->where('estatus', 'Concluido');
            } else {
                $query->where('estatus', $request->estatus);
            }
        } else {
            // Si no hay filtro o seleccionó 'Todos', ocultamos los ya procesados
            $query->whereNotIn('estatus', ['Atendido', 'Turnado', 'Concluido']);
        }

        $oficios = $query->orderBy('id', 'desc')
            ->paginate(50)
            ->withQueryString();

        $unidades = UnidadAdministrativa::whereNull('inactivo')
            ->orderBy('nombre_unidad_administrativa')
            ->pluck('nombre_unidad_administrativa', 'id');

        return view('oficios.index', compact('oficios', 'request', 'unidades'));
    }
    public function notificar($id)
    {
        try {
            $oficio = Oficio::with(['solicitantes', 'areaDirigido'])->findOrFail($id);
            $emisor = Auth::user(); 

            // 1. Buscamos a los Titulares
            $titulares = User::where('rol', 'Titular de área')
                ->where('unidad_administrativa_id', $oficio->dirigido_id)
                ->whereNotNull('email')
                ->get();

            // 2. Buscamos a los Admins TI
            $adminsTI = User::where('rol', 'Administrador TI')
                ->whereNotNull('email')
                ->get();

            // --- INICIO DE RAYOS X ---
            // Si no encuentra al titular de esa área, que detenga todo y nos avise por qué
            if ($titulares->isEmpty()) {
                $nombreArea = optional($oficio->areaDirigido)->nombre_unidad_administrativa ?? 'Desconocida';
                return redirect()->back()->with('error', "El oficio es Dirigido A '{$nombreArea}', no existe 'Titular de área' que pertenezca a esa unidad y tenga correo.");
            }
            // --- FIN DE RAYOS X ---

            // 3. Juntamos ambas listas
            $destinatarios = $titulares->merge($adminsTI)->unique('id');

            if ($destinatarios->isEmpty()) {
                return redirect()->back()->with('error', 'No se puede notificar: No se encontró al Titular de Área asignada ni al Administrador de TI.');
            }

            // 4. Enviamos los correos individualmente
            foreach ($destinatarios as $destinatario) {
                Mail::to($destinatario->email)->send(new NotificarRegistroMailable($oficio, $emisor, $destinatario));
            }

            return redirect()->back()->with('success', 'Se ha notificado el registro del oficio al Titular de Área y a TI.');
            
        } catch (\Exception $e) {
            Log::error('Error al notificar registro de oficio: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al intentar enviar la notificación: ' . $e->getMessage());
        }
    }
    public function create(): View
    {
        $oficio = new Oficio();
        // Cargar unidades para el select
        $unidades = UnidadAdministrativa::whereNull('inactivo')->orderBy('nombre_unidad_administrativa')->pluck('nombre_unidad_administrativa', 'id');

        // Retornamos la vista unificada 'form'
        return view('oficios.form', compact('oficio', 'unidades'));
    }

    public function store(Request $request): RedirectResponse

    {
        $request->validate([
            'numero_oficio' => 'required|string|max:50|unique:oficios,numero_oficio',
            'fecha_recepcion' => 'required|date',
            'dirigido_id' => 'required|integer',
            'area_asignada_id' => 'required|integer',
            'descripción_oficio' => 'required|string',
            'url_oficio' => 'required|url',
        ], $this->mensajes);


        $oficio = Oficio::create([
            'estatus' => 'Pendiente',
            'numero_oficio' => mb_strtoupper($request->numero_oficio),
            'fecha_recepcion' => $request->fecha_recepcion,
            'dirigido_id' => $request->dirigido_id,
            'area_asignada_id' => $request->area_asignada_id,
            'descripción_oficio' => $request->descripción_oficio,
            'url_oficio' => $request->url_oficio,
            'solicitud_conjunta' => $request->has('solicitud_conjunta') ? 'X' : null,
            'fecha_creacion' => now(),
            'usuario_creacion_id' => auth()->id(),
        ]);

        // Guardamos ID en sesión para el siguiente paso
        session(['oficio_id' => $oficio->id]);

        // Redirigimos a la ruta del SEGUNDO TAB (Solicitantes)
        return redirect()->route('oficiosolicitante.index')
            ->with('success', 'Datos generales guardados. Ahora agregue los solicitantes.');
    }

    public function edit(Oficio $oficio): View
    {
        // Guardar ID en sesión para la navegación entre tabs
        session(['oficio_id' => $oficio->id]);

        $unidades = UnidadAdministrativa::whereNull('inactivo')->orderBy('nombre_unidad_administrativa')->pluck('nombre_unidad_administrativa', 'id');

        return view('oficios.form', compact('oficio', 'unidades'));
    }

    public function update(Request $request, Oficio $oficio): RedirectResponse
    {
        $request->validate([
            'numero_oficio' => 'required|string|max:50|unique:oficios,numero_oficio,' . $oficio->id,
            'fecha_recepcion' => 'required|date',
            'dirigido_id' => 'required|integer',
            'area_asignada_id' => 'required|integer',
            'descripción_oficio' => 'required|string',
            'url_oficio' => 'required|url',
        ], $this->mensajes);
        $cantidadSolicitantes = $oficio->solicitantes()->count();

        // Si tiene más de 1 solicitante y el usuario desmarcó la casilla (no viene en el request)
        if ($cantidadSolicitantes > 1 && !$request->has('solicitud_conjunta')) {
            return back()->withInput()->with('error', 'No puedes desmarcar "Solicitud Conjunta" porque este oficio ya tiene ' . $cantidadSolicitantes . ' solicitantes asignados. Elimina los solicitantes extra primero.');
        }
        $oficio->update([
            'numero_oficio' => mb_strtoupper($request->numero_oficio),
            'fecha_recepcion' => $request->fecha_recepcion,
            'dirigido_id' => $request->dirigido_id,
            'area_asignada_id' => $request->area_asignada_id,
            'descripción_oficio' => $request->descripción_oficio,
            'url_oficio' => $request->url_oficio,
            'solicitud_conjunta' => $request->has('solicitud_conjunta') ? 'X' : null,
            'fecha_modificacion' => now(),
            'usuario_modificacion_id' => auth()->id(),
        ]);

        return redirect()->route('oficio.index')
            ->with('success', 'Oficio actualizado correctamente.');
    }

    public function destroy(Oficio $oficio): RedirectResponse
    {
        // Validar si tiene relaciones antes de borrar si es necesario, o usar Cascade en BD
        $oficio->delete();
        return redirect()->route('oficio.index')->with('success', 'Oficio eliminado.');
    }
}
