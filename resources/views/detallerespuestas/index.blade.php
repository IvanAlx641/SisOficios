@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <div class="card w-100 position-relative bg-white border-0 shadow-sm mb-3">
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
                        <label class="form-label fw-bold text-guinda2 small mb-0">Fecha del oficio:</label>
                        <span>{{ $oficio->fecha_recepcion ? $oficio->fecha_recepcion->format('d/m/Y') : 'N/A' }}</span>
                    </div>

                    <div class="col-md-12 mb-2">
                        <label class="form-label fw-bold text-guinda2 small mb-0">Número del oficio:</label>
                        <span>{{ $oficio->numero_oficio }}</span>
                    </div>

                    <div class="col-md-12 mb-2">
                        <label class="form-label fw-bold text-guinda2 small mb-0">Solicitantes:</label>
                        <ul class="list-unstyled mt-2 mb-0">
                            @forelse($oficio->solicitantes as $solicitante)
                                <li class="mb-1">
                                    <i class="ti ti-check text-success fs-5 me-2 align-middle"></i>
                                    <span>{{ $solicitante->nombre }}</span>
                                </li>
                            @empty
                                <li class="fst-italic text-muted small">Sin solicitantes asignados.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="col-md-12 mt-2">
                        <label class="form-label fw-bold text-guinda2 small mb-0">Sistema:</label>
                        <span>{{ $oficio->sistema->nombre_sistema ?? 'N/A' }}</span>
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
                                <th class="ps-4 py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Número de oficio de respuesta</h6></th>
                                <th class="py-3 text-center"><h6 class="fs-4 fw-bold mb-0 text-white">Fecha del oficio<br>de respuesta</h6></th>
                                <th class="py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Dirigido a</h6></th>
                                <th class="py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Firmado por</h6></th>
                                <th class="py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Descripción breve de la respuesta</h6></th>
                                <th class="text-center py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Ver acuse de<br>respuesta</h6></th>
                                <th class="text-center py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Eliminar</h6></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($oficio->respuestasOficios as $respuesta)
                                <tr>
                                    <td class="ps-4">
                                        <a href="#" class="fw-bold fs-4 text-guinda text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalEditarRespuesta{{ $respuesta->id }}" title="Editar respuesta">
                                            {{ $respuesta->numero_oficio_respuesta }}
                                        </a>
                                    </td>
                                    <td class="text-center text-muted small">
                                        {{ $respuesta->fecha_respuesta ? $respuesta->fecha_respuesta->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="small text-muted text-uppercase">{{ $respuesta->dirigidoA->nombre ?? 'N/A' }}</td>
                                    <td class="small text-muted text-uppercase">{{ $respuesta->firmadoPor->nombre ?? 'N/A' }}</td>
                                    <td class="small text-muted text-justify">
                                        {!! strip_tags($respuesta->descripción_respuesta_oficio) !!}
                                    </td>
                                    <td class="text-center">
                                        @if ($respuesta->url_oficio_respuesta)
                                            <a href="{{ $respuesta->url_oficio_respuesta }}" target="_blank" class="text-guinda fs-4" title="Ver documento">
                                                <i class="ti ti-file-text"></i>
                                            </a>
                                        @else
                                            <span class="text-muted"><i class="ti ti-minus"></i></span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('respuestas.destroy', $respuesta->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta respuesta?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn border-0 bg-transparent text-guinda fs-4 p-0" title="Eliminar respuesta">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

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
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold small">Fecha de la respuesta:</label>
                                                            <input type="date" name="fecha_respuesta"
                                                                value="{{ old('fecha_respuesta', $respuesta->fecha_respuesta ? $respuesta->fecha_respuesta->format('Y-m-d') : '') }}"
                                                                class="form-control border-guinda @error('fecha_respuesta') is-invalid @enderror">
                                                        </div>

                                                        <div class="col-md-8">
                                                            <label class="form-label fw-bold small">Núm. de oficio de respuesta:</label>
                                                            <input type="text" name="numero_oficio_respuesta"
                                                                value="{{ old('numero_oficio_respuesta', $respuesta->numero_oficio_respuesta) }}"
                                                                class="form-control border-guinda @error('numero_oficio_respuesta') is-invalid @enderror"
                                                                placeholder="Ej. 21808000020000L-001/2026">
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold small">Dirigido a:</label>
                                                            <select name="dirigido_a_id" class="form-select border-guinda @error('dirigido_a_id') is-invalid @enderror">
                                                                <option value="">Seleccione a quién va dirigido...</option>
                                                                @foreach ($usuarios as $id => $nombre)
                                                                    <option value="{{ $id }}" {{ old('dirigido_a_id', $respuesta->dirigido_a_id) == $id ? 'selected' : '' }}>
                                                                        {{ mb_strtoupper($nombre) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold small">Firmado por:</label>
                                                            <select name="firmado_por_id" class="form-select border-guinda @error('firmado_por_id') is-invalid @enderror">
                                                                <option value="">Seleccione quién firma...</option>
                                                                @foreach ($usuarios as $id => $nombre)
                                                                    <option value="{{ $id }}" {{ old('firmado_por_id', $respuesta->firmado_por_id) == $id ? 'selected' : '' }}>
                                                                        {{ mb_strtoupper($nombre) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label class="form-label fw-bold small">URL del documento de respuesta (Opcional):</label>
                                                            <input type="url" name="url_oficio_respuesta"
                                                                value="{{ old('url_oficio_respuesta', $respuesta->url_oficio_respuesta) }}"
                                                                class="form-control border-guinda @error('url_oficio_respuesta') is-invalid @enderror"
                                                                placeholder="https://...">
                                                        </div>

                                                        <div class="col-md-12 mb-2">
                                                            <label class="form-label fw-bold small">Descripción de la respuesta:</label>
                                                            <textarea name="descripción_respuesta_oficio" rows="5"
                                                                placeholder="Escriba la descripción de la respuesta aquí..."
                                                                class="form-control border-guinda @error('descripción_respuesta_oficio') is-invalid @enderror">{{ old('descripción_respuesta_oficio', $respuesta->descripción_respuesta_oficio) }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center pt-3 border-top mt-3">
                                                        <button type="submit" class="btn btn-guardar-modal rounded-pill px-4 py-2 me-3 shadow-sm">Actualizar</button>
                                                        <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                <div class="modal-header bg-white border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-guinda">Agregar Respuesta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-white">

                    <div class="bg-white p-3 rounded border border-guinda mb-4 shadow-sm">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <small class="text-muted d-block mb-1">Número de oficio:</small>
                                <span class="text-guinda fw-bold">{{ $oficio->numero_oficio }}</span>
                            </div>
                            
                            <div class="col-md-6 mb-2">
                                <small class="text-muted d-block mb-1">Fecha de recepción:</small>
                                <span class="text-guinda fw-bold">{{ $oficio->fecha_recepcion ? $oficio->fecha_recepcion->format('d/m/Y') : 'N/A' }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <small class="text-muted d-block mb-1">Sistema asociado:</small>
                                <span class="text-guinda fw-bold">{{ $oficio->sistema->nombre_sistema ?? 'N/A' }}</span>
                            </div>
                            <div class="col-12 mt-2">
                                <small class="text-muted d-block mb-1">Solicitantes:</small>
                                @foreach ($oficio->solicitantes as $sol)
                                    <div class="text-dark small"><i class="ti ti-check text-success me-1"></i>
                                        {{ $sol->nombre }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <hr class="border-secondary mb-4">

                    <form action="{{ route('respuestas.store', $oficio->id) }}" method="POST" id="formRespuesta" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Fecha de la respuesta:</label>
                                <input type="date" name="fecha_respuesta"
                                    value="{{ old('fecha_respuesta', date('Y-m-d')) }}"
                                    class="form-control border-guinda @error('fecha_respuesta') is-invalid @enderror">
                                @error('fecha_respuesta')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <label class="form-label link-hover-guinda fw-bold small">Núm. de oficio de respuesta:</label>
                                <input type="text" name="numero_oficio_respuesta"
                                    value="{{ old('numero_oficio_respuesta') }}"
                                    class="form-control border-guinda @error('numero_oficio_respuesta') is-invalid @enderror"
                                    placeholder="Ej. 21808000020000L-001/2026">
                                @error('numero_oficio_respuesta')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Dirigido a:</label>
                                <select name="dirigido_a_id"
                                    class="form-select border-guinda @error('dirigido_a_id') is-invalid @enderror">
                                    <option value="">Seleccione a quién va dirigido...</option>
                                    @foreach ($usuarios as $id => $nombre)
                                        <option value="{{ $id }}"
                                            {{ old('dirigido_a_id') == $id ? 'selected' : '' }}>
                                            {{ mb_strtoupper($nombre) }}</option>
                                    @endforeach
                                </select>
                                @error('dirigido_a_id')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Firmado por:</label>
                                <select name="firmado_por_id"
                                    class="form-select border-guinda @error('firmado_por_id') is-invalid @enderror">
                                    <option value="">Seleccione quién firma...</option>
                                    @foreach ($usuarios as $id => $nombre)
                                        <option value="{{ $id }}"
                                            {{ old('firmado_por_id') == $id ? 'selected' : '' }}>
                                            {{ mb_strtoupper($nombre) }}</option>
                                    @endforeach
                                </select>
                                @error('firmado_por_id')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold small">URL del documento de respuesta
                                    (Opcional):</label>
                                <input type="url" name="url_oficio_respuesta"
                                    value="{{ old('url_oficio_respuesta') }}"
                                    class="form-control border-guinda @error('url_oficio_respuesta') is-invalid @enderror"
                                    placeholder="https://...">
                                @error('url_oficio_respuesta')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-2">
                                <label class="form-label fw-bold small">Descripción de la respuesta:</label>
                                <textarea name="descripción_respuesta_oficio" rows="5"
                                    placeholder="Escriba la descripción de la respuesta aquí..."
                                    class="form-control border-guinda @error('descripción_respuesta_oficio') is-invalid @enderror">{{ old('descripción_respuesta_oficio') }}</textarea>
                                @error('descripción_respuesta_oficio')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex align-items-center pt-3 border-top mt-2">
                            <button type="submit"
                                class="btn btn-guardar-modal rounded-pill px-4 py-2 me-3 shadow-sm">Guardar
                            </button>
                            <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any() && request()->isMethod('post'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                setTimeout(function() {
                    // Por simplicidad, este script abre el modal de agregar si hay errores en el POST (creación).
                    // Si requieres que abra el de edición al fallar el PUT, requerirías enviar una variable de sesión.
                    if (typeof jQuery !== 'undefined') {
                        $('#modalAgregarRespuesta').modal('show');
                    } else {
                        var myModal = new bootstrap.Modal(document.getElementById('modalAgregarRespuesta'));
                        myModal.show();
                    }
                }, 300);
            });
        </script>
    @endif

    <style>
        .link-hover-guinda {
            color: grey !important;
            /* Negro/Gris muy oscuro por defecto */
            text-decoration: none;
            transition: all 0.2s ease;
        }


        .btn-guardar-modal { background-color: #9D2449; color: white; border: none; font-weight: 600; transition: all 0.2s ease; }
        .btn-guardar-modal:hover { background-color: #7a1c38; color: white; }

        .btn-cancelar { background: transparent; border: none; color: #6c757d; font-weight: 600; padding: 0; transition: all 0.2s ease; }
        .btn-cancelar:hover { color: #9D2449; text-decoration: underline; }
    </style>
@endsection