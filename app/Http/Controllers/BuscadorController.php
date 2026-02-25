<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\User; // Asumiendo que dirigidos/solicitantes son usuarios
use App\Models\Sistema; // Asumiendo que tienes un modelo Sistema
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;

class BuscadorController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Obtener datos para los selects (Ajusta los modelos según tu BD)
        $usuarios = User::pluck('nombre', 'id'); 
        $sistemas = Sistema::pluck('nombre_sistema', 'id');
        
        // 2. Aplicar filtros de búsqueda
        $oficios = $this->setQuery($request)->paginate(10);

        // Mantenemos los parámetros de búsqueda en la paginación
        $oficios->appends($request->all());

        return view('buscador.index', compact('oficios', 'usuarios', 'sistemas', 'request'));
    }

    public function show(string $id): View
    {
        // 1. CORREGIDO: Usamos 'areaDirigido' y 'tipoRequerimiento' tal como están en tu modelo
        $oficio = Oficio::with([
            'sistema', 
            'solicitantes', 
            'areaDirigido', 
            'tipoRequerimiento', 
            'respuestasOficios'
        ])->findOrFail($id);
        
        return view('buscador.fichaoficio', compact('oficio'));
    }

    private function setQuery(Request $request): Builder
    {
        // 2. CORREGIDO: Cargamos las relaciones desde el inicio para que el Popover de respuestas funcione en el index
        $query = Oficio::with(['sistema', 'solicitantes', 'areaDirigido', 'tipoRequerimiento', 'respuestasOficios']);

        if ($request->filled('numero_oficio')) {
            $query->where('numero_oficio', 'like', '%' . $request->numero_oficio . '%');
        }

        if ($request->filled('fecha_del')) {
            $query->whereDate('fecha_recepcion', '>=', $request->fecha_del);
        }

        if ($request->filled('fecha_al')) {
            $query->whereDate('fecha_recepcion', '<=', $request->fecha_al);
        }

        // 3. CORREGIDO: Tu campo en BD se llama 'dirigido_id', no 'dirigido_a_id'
        if ($request->filled('dirigido_id') && $request->dirigido_id != 'Todos') {
            $query->where('dirigido_id', $request->dirigido_id);
        }

        if ($request->filled('estatus') && $request->estatus != 'Todos') {
            $query->where('estatus', $request->estatus);
        }

        if ($request->filled('sistema_id') && $request->sistema_id != 'Todos') {
            $query->where('sistema_id', $request->sistema_id);
        }

        // 4. CORREGIDO: Tu campo en BD se llama 'tipo_requerimiento_id'
        if ($request->filled('tipo_requerimiento') && $request->tipo_requerimiento_id != 'Todos') {
            $query->where('tipo_requerimiento', $request->tipo_requerimiento_id);
        }

        // 5. CORREGIDO: Tu campo en BD se llama 'descripción_oficio'
        if ($request->filled('descripcion')) {
            $query->where('descripción_oficio', 'like', '%' . $request->descripcion . '%');
        }

        return $query->orderBy('id', 'DESC'); 
    }
}