<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\User;
use App\Models\Seguimiento;
use App\Models\ResponsableOficio;
use App\Models\UnidadAdministrativa;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Mail\NotificarSeguimientoMailable;

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
        ])->has('responsablesOficios')
            ->has('solicitantes');
        // ==========================================
        //  FILTRO DE SEGURIDAD: RESPONSABLE Y TITULAR
        // ==========================================
        $usuario = auth()->user();

        // Si es Responsable, SOLO ve los oficios donde él es el responsable asignado
        if ($usuario->rol === 'Responsable') {
            $query->whereHas('responsablesOficios', function ($q) use ($usuario) {
                $q->where('responsable_id', $usuario->id);
            });
        }
        // Si es Titular de área, SOLO ve los oficios dirigidos a su área
        elseif ($usuario->rol === 'Titular de área') {
            $query->where('dirigido_id', $usuario->unidad_administrativa_id);
        }
        // ==========================================


        // --- FILTROS DE BÚSQUEDA ---
        if ($request->filled('numero_oficio')) {
            $query->where('numero_oficio', 'like', '%' . $request->numero_oficio . '%');
        }

        if ($request->filled('dirigido_id') && $request->dirigido_id != 0) {
            $query->where('dirigido_id', $request->dirigido_id);
        }

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
    /**
     * Notifica al responsable asignado para generar respuesta.
     */
    public function notificarRespuesta($id)
    {
        try {
            $oficio = Oficio::with(['responsablesOficios.responsable', 'sistema'])->findOrFail($id);
            
            if ($oficio->estatus !== 'Concluido') {
                return redirect()->back()->with('error', 'El oficio debe estar en estatus "Concluido" para solicitar una respuesta.');
            }

            $emisor = Auth::user(); 
            $destinatarios = collect(); // Creamos una colección vacía para juntar a todos

            // 1. Buscamos a los Capturistas (asumo que es un rol en tu tabla users)
            $capturistas = User::where('rol', 'Capturista')->whereNotNull('email')->get();
            $destinatarios = $destinatarios->merge($capturistas);

            // 2. Buscamos a los Titulares del área del responsable
            foreach ($oficio->responsablesOficios as $ro) {
                if ($ro->genera_respuesta === 'X' && $ro->responsable) {
                    $titulares = User::where('rol', 'Titular de área')
                        ->where('unidad_administrativa_id', $ro->responsable->unidad_administrativa_id)
                        ->whereNotNull('email')
                        ->get();
                    
                    $destinatarios = $destinatarios->merge($titulares);
                }
            }

            // 3. Quitamos duplicados (por si alguien tiene más de un rol)
            $destinatarios = $destinatarios->unique('id');

            if ($destinatarios->isEmpty()) {
                return redirect()->back()->with('error', 'No se encontró ningún Capturista ni Titular de Área con correo válido.');
            }

            $enviados = 0;
            // 4. Enviamos el correo a nuestra lista final
            foreach ($destinatarios as $destinatario) {
                Mail::to($destinatario->email)->send(new NotificarSeguimientoMailable($oficio, $emisor, $destinatario));
                $enviados++;
            }

            return redirect()->back()->with('success', "Se ha enviado la notificación y el archivo adjunto a $enviados destinatarios (Titulares y Capturistas).");

        } catch (\Exception $e) {
            Log::error('Error al notificar respuesta: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error: ' . $e->getMessage());
        }
    }
    
    public function concluir(Request $request, Oficio $oficio): RedirectResponse
    {
        $reglas = [
            'fecha_conclusion' => 'required|date',
            'propuesta_respuesta' => 'required|string',
        ];

        if (empty($oficio->soporte_documental)) {
            $reglas['soporte_documental'] = 'file|mimes:pdf|max:20480';
        } else {
            $reglas['soporte_documental'] = 'nullable|file|mimes:pdf|max:20480';
        }

        $request->validate($reglas, [
            'fecha_conclusion.required' => 'El campo fecha de conclusión es obligatorio.',
            'propuesta_respuesta.required' => 'El campo texto de la propuesta es obligatorio.',
            'soporte_documental.required' => 'El campo soporte documental es obligatorio.',
            'soporte_documental.mimes' => 'El soporte documental debe ser un archivo de tipo: pdf, jpg, jpeg, png, doc, docx.',
            'soporte_documental.max' => 'El campo soporte documental no debe pesar más de 20MB.'
        ]);

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
