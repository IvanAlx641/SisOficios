<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Seguimiento;
use App\Models\ResponsableOficio;
use App\Models\UnidadAdministrativa;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class SeguimientoController extends Controller
{
    public function index(Request $request): View
    {
        $query = Oficio::with([
            'areaDirigido',
            'solicitantes',
            'areaAsignada',
            'sistema',
            'tipoRequerimiento',
            'responsablesOficios.responsable',
            'responsablesOficios.seguimientos',
            'oficiosVinculados'
        ]);

        if ($request->filled('numero_oficio')) {
            $query->where('numero_oficio', 'like', '%' . $request->numero_oficio . '%');
        }

        // CORRECCIÓN 1: Agregamos la validación del "0"
        if ($request->filled('dirigido_id') && $request->dirigido_id != 0) {
            $query->where('dirigido_id', $request->dirigido_id);
        }

        // ¡Tus fechas aquí ya estaban perfectas!
        if ($request->filled('fecha_recepcion')) {
            $query->whereDate('fecha_recepcion', '>=', $request->fecha_recepcion);
        }

        if ($request->filled('fecha_recepcion_fin')) {
            $query->whereDate('fecha_recepcion', '<=', $request->fecha_recepcion_fin);
        }

        if ($request->filled('estatus') && $request->estatus !== 'Todos') {
            $query->where('estatus', $request->estatus);
        } else {
            // OJO: Recuerda que si el oficio es "Pendiente", no saldrá aquí a menos que elijan "Todos"
            $query->whereIn('estatus', ['Turnado', 'Concluido', 'En validación']);
        }

        // CORRECCIÓN 2: Agregamos withQueryString() para que no se borren los filtros al cambiar de página
        $oficios = $query->orderBy('id', 'desc')
            ->paginate(50)
            ->withQueryString();

        $unidades = UnidadAdministrativa::whereNull('inactivo')
            ->orderBy('nombre_unidad_administrativa')
            ->pluck('nombre_unidad_administrativa', 'id');

        $listaOficios = Oficio::orderBy('numero_oficio')->pluck('numero_oficio', 'id');

        return view('seguimientos.index', compact('oficios', 'request', 'unidades', 'listaOficios'));
    }

    public function storeAvance(Request $request, $responsableOficioId): RedirectResponse
    {
        // Validación con mensajes en español
        $request->validate([
            'estatus' => 'required|in:Pendiente,En Desarrollo,En validación,Publicado',
            'observaciones' => 'required|string|max:2000',
        ], [
            'observaciones.required' => 'El campo observaciones es obligatorio.',
            'estatus.required' => 'El campo estatus es obligatorio.'
        ]);

        Seguimiento::create([
            'responsable_oficio_id' => $responsableOficioId,
            'fecha_seguimiento' => now(),
            'estatus' => $request->estatus,
            'observaciones' => $request->observaciones,
            'fecha_creacion' => now(),
            'usuario_creacion_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Avance registrado en la línea de tiempo.');
    }

    public function concluir(Request $request, Oficio $oficio): RedirectResponse
    {
        // 1. Reglas base
        $reglas = [
            'fecha_conclusion' => 'required|date',
            'propuesta_respuesta' => 'required|string',
        ];

        // 2. Regla dinámica para el archivo
        // Si no tiene un documento previo, es OBLIGATORIO subir uno nuevo.
        if (empty($oficio->soporte_documental)) {
            $reglas['soporte_documental'] = 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:20480';
        } else {
            $reglas['soporte_documental'] = 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:20480';
        }

        // 3. Ejecutamos la validación
        $request->validate($reglas, [
            'fecha_conclusion.required' => 'El campo fecha de conclusión es obligatorio.',
            'propuesta_respuesta.required' => 'El campo texto de la propuesta es obligatorio.',
            'soporte_documental.required' => 'El campo soporte documental es obligatorio.',
            'soporte_documental.mimes' => 'El soporte documental debe ser un archivo de tipo: pdf, jpg, jpeg, png, doc, docx.',
            'soporte_documental.max' => 'El campo soporte documental no debe pesar más de 20MB.'
        ]);

        // ... EL RESTO DE TU CÓDIGO SE QUEDA IGUAL ...
        $dataUpdate = [
            'estatus' => 'Concluido',
            'fecha_conclusion' => $request->fecha_conclusion,
            'propuesta_respuesta' => $request->propuesta_respuesta,
            'alcance_otro_oficio' => $request->has('alcance_otro_oficio') ? 'X' : null,
            'fecha_modificacion' => now(),
            'usuario_modificacion_id' => auth()->id(),
        ];

        if ($request->hasFile('soporte_documental')) {
            $path = $request->file('soporte_documental')->store('soportes', 'public');
            $dataUpdate['soporte_documental'] = $path;
        }

        $oficio->update($dataUpdate);

        if ($request->has('alcance_otro_oficio')) {
            $vinculados = $request->input('oficios_vinculados', []);
            $oficio->oficiosVinculados()->sync($vinculados);
        } else {
            $oficio->oficiosVinculados()->detach();
        }

        return redirect()->back()->with('success', '¡El oficio ha sido concluido/actualizado exitosamente!');
    }
}
