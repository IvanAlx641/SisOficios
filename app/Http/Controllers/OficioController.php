<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\UnidadAdministrativa;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OficioController extends Controller
{
    // Mensajes de error en español
    protected $mensajes = [
        'required' => 'El campo :attribute es obligatorio.',
        'unique' => 'Este número de oficio ya existe.',
        'date' => 'Ingrese una fecha válida.',
        'url' => 'Ingrese una URL válida (http://...).',
        'integer' => 'Seleccione una opción válida.',
    ];

    public function index(Request $request): View
    {
        $query = Oficio::with(['areaDirigido', 'solicitantes', 'areaAsignada']);

        // --- FILTROS ---

        // 1. Número de Oficio
        if ($request->filled('numero_oficio')) {
            $query->where('numero_oficio', 'like', '%' . $request->numero_oficio . '%');
        }

        // 2. Dirigido A (Ignora si está vacío o null)
        if ($request->filled('dirigido_id') && $request->dirigido_id != 0) {
            $query->where('dirigido_id', $request->dirigido_id);
        }

        // 3. Fecha de Recepción
        if ($request->filled('fecha_recepcion')) {
            $query->whereDate('fecha_recepcion', $request->fecha_recepcion);
        }
        $query->where('estatus', '!=', 'Atendido');
        // 4. Estatus (Si es 'Todos', no filtramos nada)
        if ($request->filled('estatus') && $request->estatus !== 'Todos') {
            $query->where('estatus', $request->estatus);
        }
        
        
        // Ordenar por ID descendente (los más nuevos primero)
        $oficios = $query->orderBy('id', 'desc')
                ->paginate(50)
                ->withQueryString();
        
        // Cargar catálogo para el select
        $unidades = UnidadAdministrativa::whereNull('inactivo')
            ->orderBy('nombre_unidad_administrativa')
            ->pluck('nombre_unidad_administrativa', 'id');
            

        return view('oficios.index', compact('oficios', 'request', 'unidades'));
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