@extends('layouts.admin')

@section('content')

    <div class="card border-0 shadow-sm rounded-3">

        <div class="card-body pt-2 py-3 bg-light">
            <div class="row align-items-center">
                <div class="col-9">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('turno.index') }}" class="text-guinda text-decoration-none me-2"
                            title="Volver al listado">
                            <i class="ti ti-arrow-back-up fs-3"></i>
                        </a>
                        <h4 class="fw-bold mb-0 text-guinda">Turno</h4>
                    </div>
                </div>
                <div class="col-3 text-end">
                    <button class="btn btn-guinda w-75 py-2 shadow-sm rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#modalResponsable" onclick="abrirModalCrear()" style="text-transform: none;">
                        Agregar responsable
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-4 bg-white">
            <ul class="nav nav-tabs border-bottom-0">
                <li class="nav-item">
                    <a class="nav-link text-muted" href="{{ route('turno.edit', $oficio->id) }}">
                        <i class="ti ti-file-description me-1"></i> Datos del turno
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold text-guinda border-bottom-guinda" href="#">
                        <i class="ti ti-users me-1"></i> Responsables
                    </a>
                </li>
            </ul>
            <div class="bg-white p-4 rounded-3">

                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <span class="small text-muted me-2">Número de oficio:</span>
                            <span class="small text-dark small mb-0">{{ $oficio->numero_oficio }}</span>
                            <a href="{{ $oficio->url_oficio }}" target="_blank"
                                class="ms-3 btn btn-sm btn-outline-guinda py-0 px-2 rounded-pill">
                                <i class="ti ti-eye"></i> Ver oficio
                            </a>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <span class="small text-muted me-2">Fecha de recepción:</span>
                            <span class="small text-dark mb-0">{{ $oficio->fecha_recepcion->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <span class="small text-muted me-2">Dirigido a:</span>
                            <span class="small text-dark mb-0">
                                {{ optional($oficio->areaDirigido)->nombre_unidad_administrativa ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-12">
                        <span class="small text-muted me-2">Sistema:</span>
                        <span class="text-dark small mb-0">{{ $oficio->sistema->sigla_sistema ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small me-2">Tipo de requerimiento:</span>
                        <span
                            class="text-dark small mb-0">{{ $oficio->tipoRequerimiento->tipo_requerimiento ?? 'N/A' }}</span>
                    </div>

                    <div class="col-12">
                        <p class="mb-1 small text-muted">Descripción del requerimiento:</p>
                        <div class="text-dark small bg-white p-2 mb-0">
                            {{ $oficio->descripción_oficio }}
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-start">
                            <span class="small text-muted me-2 pt-2">Solicitantes:</span>
                            <div class="flex-grow-1">
                                @if ($oficio->solicitantes->count() > 0)
                                    <ul class="list-unstyled mb-0  p-2 ">
                                        @foreach ($oficio->solicitantes as $solicitante)
                                            <li>

                                                <span class="fw-bold">{{ $solicitante->nombre }}</span>
                                                <span class="text-muted small">({{ $solicitante->cargo }})</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted fst-italic mb-0 pt-2">Sin solicitantes asignados.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-items-stretch">
                @forelse ($responsablesOficios as $ro)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm w-100">
                            <div class="card-body d-flex flex-column position-relative p-4">

                                <h6 class="fw-bold text-guinda pe-4 mb-2">
                                    {{ $ro->responsable->nombre ?? 'Usuario Desconocido' }}
                                </h6>
                                <hr class="opacity-25 my-2 border-guinda">

                                <p class="small text-muted mb-1">Unidad administrativa:</p>
                                <p class="fw-semibold small text-dark mb-3 text-wrap">
                                    {{ $ro->responsable->unidadAdministrativa->nombre_unidad_administrativa ?? 'N/A' }}
                                </p>

                                <div class="d-flex align-items-center justify-content-end gap-2 mt-auto pt-3">

                                    @if ($ro->genera_respuesta == 'X')
                                        <div class="text-success fs-4 me-auto" title="Elabora Respuesta">
                                            <i class="ti ti-check fw-bold"></i>
                                        </div>
                                    @endif

                                    <button type="button" class="btn btn-outline-guinda border-0 bg-transparent p-1"
                                        onclick="abrirModalEditar({{ $ro->id }}, '{{ route('responsable.update', $ro->id) }}')"
                                        title="Editar">
                                        <i class="ti ti-pencil fs-5"></i>
                                    </button>

                                    <form action="{{ route('responsable.destroy', $ro->id) }}" method="POST"
                                        class="m-0 p-0" onsubmit="return confirm('¿Eliminar responsable?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-guinda border-0 bg-transparent p-1"
                                            title="Eliminar">
                                            <i class="ti ti-trash fs-5"></i>
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h5 class="text-muted">No hay responsables asignados.</h5>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalResponsable" tabindex="-1" aria-hidden="true"
        @if ($errors->any()) data-bs-backdrop="static" @endif>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-light text-white">
                    <h6 class="modal-title text-guinda fw-bold" id="modalTitle">Responsable</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="formResponsable" action="{{ route('responsable.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        <div class="mb-3">
                            <label class="form-label fw-bold text-guinda2" for="modal_responsable_id">Seleccione
                                responsable:</label>
                            <select name="responsable_id" id="modal_responsable_id"
                                class="form-select border-guinda @error('responsable_id') is-invalid @enderror">
                                <option value="">Seleccione...</option>
                                @foreach ($responsables as $id => $nombre)
                                    <option value="{{ $id }}" @selected(old('responsable_id') == $id)>
                                        {{ $nombre }}
                                    </option>
                                @endforeach
                            </select>

                            @error('responsable_id')
                                <div class="invalid-feedback fw-bold d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('genera_respuesta') is-invalid @enderror"
                                    type="checkbox" name="genera_respuesta" id="genera_respuesta" value="X"
                                    @checked(old('genera_respuesta') == 'X')>
                                <label class="form-check-label small text-dark mb-0" for="genera_respuesta">
                                    ¿Elabora respuesta?
                                </label>
                                @error('genera_respuesta')
                                    <div class="invalid-feedback fw-bold d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if ($estadisticaRespuestas->count() > 0)
                            <div
                                class="alert bg-warning-subtle text-warning-emphasis border-warning-subtle p-3 rounded-3 small">
                                <strong>Respuestas conjuntas elaboradas:</strong>
                                <ul class="mb-0 mt-2 ps-3">
                                    @foreach ($estadisticaRespuestas as $est)
                                        <li>{{ $est->nombre }}: <span class="fw-bold">{{ $est->total }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="text-left">
                            <button type="submit" class="btn btn-guinda">Guardar</button>
                            <button type="button" class="btn btn-cancelar me-2 fw-bold" data-bs-dismiss="modal"
                                onclick="document.getElementById('formResponsable').reset(); limpiarErrores();">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .border-bottom-guinda {
            border-bottom: 3px solid #9D2449 !important;
            background-color: transparent !important;
        }

        .nav-tabs .nav-link:hover {
            color: #9D2449;
            border-color: transparent;
        }
    </style>

    <script>
        // Función agresiva para limpiar errores y valores manualmente
        function limpiarErrores() {
            // 1. Quitar los bordes rojos de los inputs
            const invalidInputs = document.querySelectorAll('#formResponsable .is-invalid');
            invalidInputs.forEach(input => input.classList.remove('is-invalid'));

            // 2. Forzar el ocultamiento de los textos de error de Laravel
            const errorTexts = document.querySelectorAll('#formResponsable .invalid-feedback');
            errorTexts.forEach(text => {
                text.style.display = 'none';
                text.classList.remove('d-block');
            });

            // 3. Limpiar valores manualmente (bypasseando el old() de Laravel)
            const selectResp = document.getElementById('modal_responsable_id');
            if (selectResp) {
                selectResp.value = '';
                // Si hay un buscador custom JS, reiniciamos su texto visual
                if (typeof convertirSelectABuscador === 'function' && selectResp.nextElementSibling) {
                    const trigger = selectResp.nextElementSibling.querySelector('.searchable-trigger');
                    if (trigger) trigger.textContent = 'Seleccione...';
                }
            }

            const checkRespuesta = document.getElementById('genera_respuesta');
            if (checkRespuesta) {
                checkRespuesta.checked = false;
            }
        }

        function abrirModalCrear() {
            limpiarErrores(); // Se limpia todo antes de abrir
            document.getElementById('formResponsable').action = "{{ route('responsable.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('modalTitle').innerText = 'Agregar responsable';

            if (typeof convertirSelectABuscador === 'function') {
                convertirSelectABuscador('modal_responsable_id');
            }
        }

        function abrirModalEditar(id, urlActualizar) {
            limpiarErrores();
            document.getElementById('formResponsable').action = urlActualizar;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('modalTitle').innerText = 'Editar responsable';

            fetch(`/responsable/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal_responsable_id').value = data.responsable_id;
                    document.getElementById('genera_respuesta').checked = (data.genera_respuesta === 'X');

                    if (typeof convertirSelectABuscador === 'function') {
                        const select = document.getElementById('modal_responsable_id');
                        if (select && select.nextElementSibling) {
                            const trigger = select.nextElementSibling.querySelector('.searchable-trigger');
                            if (trigger) trigger.textContent = select.options[select.selectedIndex].text;
                        }
                    }

                    var modal = new bootstrap.Modal(document.getElementById('modalResponsable'));
                    modal.show();
                })
                .catch(error => console.error("Error obteniendo datos:", error));
        }

        document.addEventListener("DOMContentLoaded", function() {
            if (typeof convertirSelectABuscador === 'function') {
                convertirSelectABuscador('modal_responsable_id');
            }

            // Evento global: Si el usuario presiona "Cancelar", la "X", o da clic fuera del modal, se limpia.
            const modalEl = document.getElementById('modalResponsable');
            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', function() {
                    limpiarErrores();
                });
            }

            // Si Laravel detecta un error al recargar, se abre el modal automáticamente
            @if ($errors->any())
                var modal = new bootstrap.Modal(document.getElementById('modalResponsable'));
                modal.show();
            @endif
        });
    </script>

@endsection
