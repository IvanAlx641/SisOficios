@extends('layouts.admin')

@section('content')

    <div class="card border-0 shadow-sm rounded-3">

        <div class="card-header bg-light border-bottom-0 pt-4 px-4">

            <h4 class="fw-bold text-guinda mb-3">Turno de oficio

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
        </div>

        <div class="card-body p-4 bg-light">
            <div class="bg-white p-4 rounded-3 shadow-sm border mb-4">
            <h5 class="fw-bold text-guinda mb-3 border-bottom pb-2">Contexto del oficio</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <p class="mb-1 small text-muted">Número de oficio:</p>
                    <p class="fw-bold fs-5 text-dark mb-0">
                        {{ $oficio->numero_oficio }}
                        <a href="{{ $oficio->url_oficio }}" target="_blank"
                            class="ms-2 btn btn-sm btn-outline-guinda py-0 px-2 rounded-pill">
                            <i class="ti ti-eye"></i> Ver oficio
                        </a>
                    </p>
                </div>

                <div class="col-md-6">
                    <p class="mb-1 small text-muted">Fecha de recepción:</p>
                    <p class="fw-semibold text-dark mb-0">{{ $oficio->fecha_recepcion->format('d/m/Y') }}</p>
                </div>

                <div class="col-md-12">
                    <p class="mb-1 small text-muted">Dirigido a:</p>
                    <p class="fw-semibold text-dark mb-0">
                        {{ optional($oficio->areaDirigido)->nombre_unidad_administrativa ?? 'N/A' }}</p>
                </div>

                <div class="col-md-12">
                    <p class="mb-1 small text-muted">Descripción del requerimiento:</p>
                    <p class="text-dark bg-light p-2 rounded border">{{ $oficio->descripción_oficio }}</p>
                </div>

                <div class="col-md-12">
                    <p class="mb-1 small text-muted">Solicitantes:</p>
                    @if ($oficio->solicitantes->count() > 0)
                        <ul class="list-unstyled mb-0 bg-light p-2 rounded border">
                            @foreach ($oficio->solicitantes as $solicitante)
                                <li>
                                    <i class="ti ti-user text-guinda me-1"></i>
                                    <span class="fw-bold">{{ $solicitante->nombre }}</span>
                                    <span class="text-muted small">({{ $solicitante->cargo }})</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted fst-italic mb-0">Sin solicitantes asignados.</p>
                    @endif
                </div>
            </div>
        </div>
            <div class="bg-white p-3 rounded-3 shadow-sm border mb-4">
                <div class="row text-sm">
                    <div class="col-md-3 border-end">
                        <span class="text-muted d-block small">Número:</span>
                        <strong class="text-dark">{{ $oficio->numero_oficio }}</strong>
                    </div>
                    <div class="col-md-3 border-end">
                        <span class="text-muted d-block small">Sistema:</span>
                        <strong class="text-guinda">{{ $oficio->sistema->sigla_sistema ?? 'N/A' }}</strong>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted d-block small">Tipo requerimiento:</span>
                        <strong class="text-dark">{{ $oficio->tipoRequerimiento->tipo_requerimiento ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-4">
                <button class="btn btn-guinda rounded-pill px-4 shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#modalResponsable" onclick="abrirModalCrear()"style="text-transform: none;">
                    Agregar responsable
                </button>
            </div>

            <div class="row g-3">
                @forelse ($responsablesOficios as $ro)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body position-relative">

                                <h6 class="fw-bold text-guinda pe-5">
                                    {{ $ro->responsable->nombre ?? 'Usuario Desconocido' }}</h6>
                                <hr class="opacity-25 my-2 border-guinda">

                                <p class="small text-muted mb-0">Unidad administrativa:</p>
                                <p class="fw-semibold small text-dark mb-3">
                                    {{ $ro->responsable->unidadAdministrativa->nombre_unidad_administrativa ?? 'N/A' }}</p>


                                <div class="d-flex align-items-center justify-content-end gap-2 mt-auto pt-3">

                                    @if ($ro->genera_respuesta == 'X')
                                        <div class="text-success fs-4 me-1" title="Elabora Respuesta">
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
                    @empty
                        <div class="col-12 text-center py-5">
                            <h5 class="text-muted">No hay responsables asignados.</h5>
                        </div>
                @endforelse
            </div>

            <div class="mt-4 pt-3 border-top">
                <a href="{{ route('turno.index') }}" class="btn-cancelar">Finalizar</a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalResponsable" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-guinda text-white">
                    <h6 class="modal-title text-white fw-bold" id="modalTitle">Responsable</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="formResponsable" action="{{ route('responsable.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        <div class="mb-3">
                            <label class="form-label fw-bold text-guinda">Seleccione responsable:</label>
                            <select name="responsable_id" id="modal_responsable_id" class="form-select border-guinda"
                                required>
                                <option value="">Seleccione...</option>
                                @foreach ($responsables as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="genera_respuesta"
                                    id="genera_respuesta" value="X">
                                <label class="form-check-label fw-bold text-dark" for="genera_respuesta">
                                    ¿Elabora respuesta?
                                </label>
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

                        <div class="text-end">
                            <button type="button" class="btn btn-light text-danger me-2 fw-bold"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-guinda">Guardar</button>
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
        // JS para manejar Formulario Modal Dinámico (Crear/Editar)
        function abrirModalCrear() {
            document.getElementById('formResponsable').action = "{{ route('responsable.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('modalTitle').innerText = 'Agregar responsable';
            document.getElementById('modal_responsable_id').value = '';
            document.getElementById('genera_respuesta').checked = false;

            // Si usas el buscador JS
            if (typeof convertirSelectABuscador === 'function') {
                convertirSelectABuscador('modal_responsable_id');
            }
        }

        function abrirModalEditar(id, urlActualizar) {
            document.getElementById('formResponsable').action = urlActualizar;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('modalTitle').innerText = 'Editar responsable';

            // Petición AJAX para obtener datos y llenar
            fetch(`/responsable/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal_responsable_id').value = data.responsable_id;
                    document.getElementById('genera_respuesta').checked = (data.genera_respuesta === 'X');

                    // Actualizar el buscador JS visualmente si está activo
                    if (typeof convertirSelectABuscador === 'function') {
                        // Forzamos un evento change falso para que el texto visual se actualice si usas el script custom
                        const select = document.getElementById('modal_responsable_id');
                        const trigger = select.nextElementSibling.querySelector('.searchable-trigger');
                        if (trigger) trigger.textContent = select.options[select.selectedIndex].text;
                    }

                    var modal = new bootstrap.Modal(document.getElementById('modalResponsable'));
                    modal.show();
                });
        }

        document.addEventListener("DOMContentLoaded", function() {
            if (typeof convertirSelectABuscador === 'function') {
                convertirSelectABuscador('modal_responsable_id');
            }
        });
    </script>

@endsection
