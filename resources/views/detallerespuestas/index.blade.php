@extends('layouts.admin')
@section('content')
    <div class="container-fluid">

        <div class="card w-100 position-relative border-0 shadow-sm mb-3">
            <div class="card-body pt-3 pb-2 bg-light d-flex justify-content-between align-items-center border-bottom">
                <div class="d-flex align-items-center">
                    <a href="{{ route('respuestas.index') }}" class="text-guinda text-decoration-none me-3"
                        title="Volver al listado">
                        <i class="ti ti-arrow-back-up fs-3"></i>
                    </a>
                    <h4 class="fw-bold mb-0 text-guinda">Respuesta</h4>
                    
                </div>
                <button class="btn btn-guinda shadow-sm rounded-pill" data-bs-toggle="modal"
                    data-bs-target="#modalAgregarRespuesta">
                    Agregar
                </button>
            </div>

            <div class="card-body p-4">
                <div class="row form-group mb-3">
                    <div class="col-md-12 mb-2">
                        <label class="small text-muted me-2 ">Fecha del oficio:</label>
                        <span
                            class="text-black small mb-0">{{ $oficio->fecha_recepcion ? $oficio->fecha_recepcion->format('d/m/Y') : 'N/A' }}</span>
                    </div>

                    <div class="col-md-12 mb-2">
                        <label class="small text-muted me-2">Número del oficio:</label>
                        <span class="text-black small mb-0">{{ $oficio->numero_oficio }}</span>
                    </div>

                    <div class="col-md-12 mb-2">
                        <label class="small text-muted me-2">Solicitantes:</label>
                        <ul class="list-unstyled mt-2 mb-0 ms-2">
                            @forelse($oficio->solicitantes as $solicitante)
                                <li class="mb-1">
                                    <i class="ti ti-check text-success fs-5 me-2 align-middle"></i>
                                    <span class="text-black small mb-0">{{ $solicitante->nombre }}</span>
                                </li>
                            @empty
                                <li class="fst-italic text-muted small">Sin solicitantes asignados.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="col-md-12 mt-2">
                        <label class="small text-muted me-2">Sistema:</label>
                        <span class="text-black small mb-0">{{ $oficio->sistema->nombre_sistema ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card w-100 position-relative border-0 shadow-sm mt-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-guinda text-white">
                            <tr>
                                <th class="ps-4 py-3">
                                    <h6 class="text-white text-center form-label fw-bold small">Número de oficio de
                                        respuesta</h6>
                                </th>
                                <th class="py-3 text-center">
                                    <h6 class="text-white text-center form-label fw-bold small">Fecha del oficio<br>de
                                        respuesta</h6>
                                </th>
                                <th class="py-3">
                                    <h6 class="text-white text-center form-label fw-bold small">Dirigido a</h6>
                                </th>
                                <th class="py-3">
                                    <h6 class="text-white text-center form-label fw-bold small">Firmado por</h6>
                                </th>
                                <th class="py-3">
                                    <h6 class="text-white text-center form-label fw-bold small">Descripción breve de la
                                        respuesta</h6>
                                </th>
                                <th class="text-center py-3">
                                    <h6 class="text-white text-center form-label fw-bold small">Ver acuse de<br>respuesta
                                    </h6>
                                </th>
                                <th class="text-center py-3">
                                    <h6 class="text-white text-center form-label fw-bold small">Eliminar</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($oficio->respuestasOficios as $respuesta)
                                <tr>
                                    <td class="ps-4">
                                        <a href="#"
                                            class="fw-bold fs-3 link-oficio-gris text-guinda text-decoration-none"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditarRespuesta{{ $respuesta->id }}"
                                            title="Editar respuesta">
                                            {{ $respuesta->numero_oficio_respuesta }}
                                        </a>
                                    </td>
                                    <td class="text-center text-muted small">
                                        {{ $respuesta->fecha_respuesta ? $respuesta->fecha_respuesta->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="small text-muted text-uppercase">{{ $respuesta->dirigidoA->nombre ?? 'N/A' }}
                                    </td>
                                    <td class="small text-muted text-uppercase">
                                        {{ $respuesta->firmadoPor->nombre ?? 'N/A' }}</td>
                                    <td class="small text-muted text-justify">
                                        {!! strip_tags($respuesta->descripción_respuesta_oficio) !!}
                                    </td>
                                    <td class="text-center">
                                        @if ($respuesta->url_oficio_respuesta)
                                            <a href="{{ $respuesta->url_oficio_respuesta }}" target="_blank"
                                                class="text-guinda fs-4" title="Ver documento">
                                                <i class="ti ti-file-text"></i>
                                            </a>
                                        @else
                                            <span class="text-muted"><i class="ti ti-minus"></i></span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('respuestas.destroy', $respuesta->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('¿Estás seguro de eliminar esta respuesta?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn border-0 bg-transparent text-guinda fs-4 p-0"
                                                title="Eliminar respuesta">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="ti ti-file-off fs-1 text-muted d-block mb-2"></i>
                                        <span class="text-muted">Aún no se han emitido respuestas para este oficio.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAgregarRespuesta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-guinda">Respuesta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-white">

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <small class="text-muted me-1">Número de oficio:</small>
                            <span class="text-black small mb-0">{{ $oficio->numero_oficio }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted me-1">Fecha de recepción:</small>
                            <span
                                class="text-black small mb-0">{{ $oficio->fecha_recepcion ? $oficio->fecha_recepcion->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted me-1">Sistema asociado:</small>
                            <span class="text-black small mb-0">{{ $oficio->sistema->nombre_sistema ?? 'N/A' }}</span>
                        </div>

                        {{-- Para los solicitantes usamos flexbox para que la lista se alinee bien si hay más de uno --}}
                        <div class="col-12 mb-2 d-flex align-items-start">
                            <small class="text-muted me-2 mt-1">Solicitantes:</small>
                            <div>
                                @foreach ($oficio->solicitantes as $sol)
                                    <div class="text-black small mb-0">
                                        <i class="ti ti-check text-success me-1"></i> {{ $sol->nombre }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('respuestas.store', $oficio->id) }}" method="POST" novalidate>
                        @csrf
                        <input type="hidden" name="oficio_id" value="{{ $oficio->id }}">
                        <input type="hidden" name="form_action" value="create">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Fecha de la respuesta:<span
                                        class="text-danger">*</span></label>
                                <input type="date" name="fecha_respuesta"
                                    value="{{ old('fecha_respuesta', date('Y-m-d')) }}"
                                    class="form-control border-guinda {{ old('form_action') == 'create' && $errors->has('fecha_respuesta') ? 'is-invalid' : '' }}">
                                @if (old('form_action') == 'create' && $errors->has('fecha_respuesta'))
                                    <div class="invalid-feedback fw-bold">{{ $errors->first('fecha_respuesta') }}</div>
                                @endif
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-bold small">Núm. de oficio de respuesta:<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="numero_oficio_respuesta"
                                    value="{{ old('numero_oficio_respuesta') }}"
                                    class="form-control border-guinda {{ old('form_action') == 'create' && $errors->has('numero_oficio_respuesta') ? 'is-invalid' : '' }}"
                                    placeholder="Ej. 21808000020000L-001/2026">
                                @if (old('form_action') == 'create' && $errors->has('numero_oficio_respuesta'))
                                    <div class="invalid-feedback fw-bold">{{ $errors->first('numero_oficio_respuesta') }}
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-guinda2 small">Dirigido a:<span
                                        class="text-danger">*</span></label>
                                <select name="dirigido_a_id" id="dirigido_a_{{ $oficio->id }}"
                                    class="form-select border-guinda @error('dirigido_a_id') is-invalid @enderror">
                                    <option value="">Seleccione a quién va dirigido...</option>
                                    {{-- Recorremos únicamente los solicitantes de este oficio --}}
                                    @foreach ($oficio->solicitantes as $solicitante)
                                        <option value="{{ $solicitante->id }}"
                                            {{ old('dirigido_a_id', $respuesta->dirigido_a_id ?? '') == $solicitante->id ? 'selected' : '' }}>
                                            {{ mb_strtoupper($solicitante->nombre) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dirigido_a_id')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-guinda2 small">Firmado por:<span
                                        class="text-danger">*</span></label>
                                <select name="firmado_por_id" id="firmado_por_{{ $oficio->id }}"
                                    class="form-select border-guinda @error('firmado_por_id') is-invalid @enderror">
                                    <option value="">Seleccione quién firma...</option>
                                    @foreach ($titulares as $id => $nombre)
                                        <option value="{{ $id }}"
                                            {{ old('firmado_por_id', $respuesta->firmado_por_id ?? '') == $id ? 'selected' : '' }}>
                                            {{ mb_strtoupper($nombre) }}</option>
                                    @endforeach
                                </select>
                                @error('firmado_por_id')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold small">URL del documento de respuesta (Opcional):</label>
                                <input type="url" name="url_oficio_respuesta"
                                    value="{{ old('url_oficio_respuesta') }}"
                                    class="form-control border-guinda {{ old('form_action') == 'create' && $errors->has('url_oficio_respuesta') ? 'is-invalid' : '' }}"
                                    placeholder="https://...">
                                @if (old('form_action') == 'create' && $errors->has('url_oficio_respuesta'))
                                    <div class="invalid-feedback fw-bold">{{ $errors->first('url_oficio_respuesta') }}
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-12 mb-2">
                                <label class="form-label fw-bold small">Descripción de la respuesta:<span
                                        class="text-danger">*</span></label>
                                <textarea name="descripción_respuesta_oficio" rows="5"
                                    placeholder="Escriba la descripción de la respuesta aquí..."
                                    class="form-control border-guinda {{ old('form_action') == 'create' && $errors->has('descripción_respuesta_oficio') ? 'is-invalid' : '' }}">{{ old('descripción_respuesta_oficio') }}</textarea>
                                @if (old('form_action') == 'create' && $errors->has('descripción_respuesta_oficio'))
                                    <div class="invalid-feedback fw-bold">
                                        {{ $errors->first('descripción_respuesta_oficio') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center pt-2">
                            <button type="submit"
                                class="btn btn-guardar-modal rounded-pill px-4 py-2 me-3 shadow-sm">Guardar</button>
                            <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($oficio->respuestasOficios as $respuesta)
        <div class="modal fade" id="modalEditarRespuesta{{ $respuesta->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-white border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold text-guinda">Editar Respuesta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 bg-white">

                        <form action="{{ route('respuestas.update', $respuesta->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="form_action" value="edit_{{ $respuesta->id }}">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small">Fecha de la respuesta:</label>
                                    <input type="date" name="fecha_respuesta"
                                        value="{{ old('fecha_respuesta', $respuesta->fecha_respuesta ? \Carbon\Carbon::parse($respuesta->fecha_respuesta)->format('Y-m-d') : '') }}"
                                        class="form-control border-guinda {{ old('form_action') == 'edit_' . $respuesta->id && $errors->has('fecha_respuesta') ? 'is-invalid' : '' }}">
                                    @if (old('form_action') == 'edit_' . $respuesta->id && $errors->has('fecha_respuesta'))
                                        <div class="invalid-feedback fw-bold">{{ $errors->first('fecha_respuesta') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label fw-bold small">Núm. de oficio de respuesta:</label>
                                    <input type="text" name="numero_oficio_respuesta"
                                        value="{{ old('numero_oficio_respuesta', $respuesta->numero_oficio_respuesta) }}"
                                        class="form-control border-guinda {{ old('form_action') == 'edit_' . $respuesta->id && $errors->has('numero_oficio_respuesta') ? 'is-invalid' : '' }}">
                                    @if (old('form_action') == 'edit_' . $respuesta->id && $errors->has('numero_oficio_respuesta'))
                                        <div class="invalid-feedback fw-bold">
                                            {{ $errors->first('numero_oficio_respuesta') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Dirigido a:</label>
                                    <select name="dirigido_a_id" id="dirigido_a_edit_{{ $respuesta->id }}"
                                        class="form-select border-guinda {{ old('form_action') == 'edit_' . $respuesta->id && $errors->has('dirigido_a_id') ? 'is-invalid' : '' }}">
                                        <option value="">Seleccione a quién va dirigido...</option>
                                        @foreach ($oficio->solicitantes as $solicitante)
                                            <option value="{{ $solicitante->id }}"
                                                {{ old('dirigido_a_id', $respuesta->dirigido_a_id) == $solicitante->id ? 'selected' : '' }}>
                                                {{ mb_strtoupper($solicitante->nombre) }}</option>
                                        @endforeach
                                    </select>
                                    @if (old('form_action') == 'edit_' . $respuesta->id && $errors->has('dirigido_a_id'))
                                        <div class="invalid-feedback fw-bold">{{ $errors->first('dirigido_a_id') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Firmado por:</label>
                                    <select name="firmado_por_id" id="firmado_por_edit_{{ $respuesta->id }}"
                                        class="form-select border-guinda {{ old('form_action') == 'edit_' . $respuesta->id && $errors->has('firmado_por_id') ? 'is-invalid' : '' }}">
                                        <option value="">Seleccione quién firma...</option>
                                        @foreach ($titulares as $id => $nombre)
                                            <option value="{{ $id }}"
                                                {{ old('firmado_por_id', $respuesta->firmado_por_id) == $id ? 'selected' : '' }}>
                                                {{ mb_strtoupper($nombre) }}</option>
                                        @endforeach
                                    </select>
                                    @if (old('form_action') == 'edit_' . $respuesta->id && $errors->has('firmado_por_id'))
                                        <div class="invalid-feedback fw-bold">{{ $errors->first('firmado_por_id') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-bold small">URL del documento (Opcional):</label>
                                    <input type="url" name="url_oficio_respuesta"
                                        value="{{ old('url_oficio_respuesta', $respuesta->url_oficio_respuesta) }}"
                                        class="form-control border-guinda {{ old('form_action') == 'edit_' . $respuesta->id && $errors->has('url_oficio_respuesta') ? 'is-invalid' : '' }}">
                                    @if (old('form_action') == 'edit_' . $respuesta->id && $errors->has('url_oficio_respuesta'))
                                        <div class="invalid-feedback fw-bold">{{ $errors->first('url_oficio_respuesta') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-12 mb-2">
                                    <label class="form-label fw-bold small">Descripción de la respuesta:</label>
                                    <textarea name="descripción_respuesta_oficio" rows="5"
                                        class="form-control border-guinda {{ old('form_action') == 'edit_' . $respuesta->id && $errors->has('descripción_respuesta_oficio') ? 'is-invalid' : '' }}">{{ old('descripción_respuesta_oficio', $respuesta->descripcion_respuesta_oficio ?? $respuesta->descripción_respuesta_oficio) }}</textarea>
                                    @if (old('form_action') == 'edit_' . $respuesta->id && $errors->has('descripción_respuesta_oficio'))
                                        <div class="invalid-feedback fw-bold">
                                            {{ $errors->first('descripción_respuesta_oficio') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex align-items-center pt-3 border-top mt-4">
                                <button type="submit"
                                    class="btn btn-guardar-modal rounded-pill px-4 py-2 me-3 shadow-sm">Actualizar</button>
                                <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // 1. INICIALIZAR LOS BUSCADORES DE FORMA DINÁMICA
            if (typeof convertirSelectABuscador === 'function') {
                // Esto busca automáticamente tus selects por su nombre (name="") y los convierte
                var selectsParaBuscador = document.querySelectorAll(
                    'select[name="dirigido_a_id"], select[name="firmado_por_id"]');

                selectsParaBuscador.forEach(function(select) {
                    // Solo necesita que el select tenga un id="" asignado en el HTML
                    if (select.id) {
                        convertirSelectABuscador(select.id);
                    }
                });
            }

            // 2. REABRIR MODAL SI HAY ERRORES
            @if ($errors->any())
                setTimeout(function() {
                    let formAction = "{{ old('form_action') }}";
                    let modalId = '';

                    if (formAction === 'create') {
                        modalId = 'modalAgregarRespuesta';
                    } else if (formAction && formAction.startsWith('edit_')) {
                        let idRespuesta = formAction.split('_')[1];
                        modalId = 'modalEditarRespuesta' + idRespuesta;
                    }

                    if (modalId) {
                        let modalElement = document.getElementById(modalId);
                        if (modalElement) {
                            if (typeof jQuery !== 'undefined') {
                                $('#' + modalId).modal('show');
                            } else {
                                var myModal = new bootstrap.Modal(modalElement);
                                myModal.show();
                            }
                        }
                    }
                }, 300);
            @endif

            // 3. LIMPIAR VALIDACIONES AL CERRAR MODALES
            var modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                modal.addEventListener('hidden.bs.modal', function() {
                    var form = this.querySelector('form');
                    if (form) {
                        var invalidInputs = form.querySelectorAll('.is-invalid');
                        invalidInputs.forEach(input => input.classList.remove('is-invalid'));

                        var errorFeedbacks = form.querySelectorAll('.invalid-feedback');
                        errorFeedbacks.forEach(feedback => feedback.style.display = 'none');

                        form.reset();

                        // Regresa el texto visual del buscador a su estado por defecto
                        var triggers = form.querySelectorAll('.searchable-trigger');
                        triggers.forEach(trigger => trigger.textContent = 'Seleccione...');
                    }
                });
            });

        });
    </script>

    <style>
        .btn-guardar-modal {
            background-color: #9D2449;
            color: white;
            border: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-guardar-modal:hover {
            background-color: #7a1c38;
            color: white;
        }

        .btn-cancelar {
            background: transparent;
            border: none;
            color: #6c757d;
            font-weight: 600;
            padding: 0;
            transition: all 0.2s ease;
        }

        .btn-cancelar:hover {
            color: #9D2449;
            text-decoration: underline;
        }

        .link-oficio-guinda {
            color: grey;
            text-decoration: none;
            transition: 0.2s;
        }
    </style>
@endsection