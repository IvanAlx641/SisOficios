@extends('layouts.admin')

@section('content')
    <style>
        /* Ajustes responsivos exclusivos para celulares (no afectan la vista en PC) */
        @media (max-width: 767.98px) {
            .badge-filtro-btn {
                font-size: 0.7rem !important;
                padding: 0.3rem 0.5rem !important;
            }
        }
    </style>

    <div class="container-fluid px-2 px-md-3">

        <div class="card w-100 position-relative border-0 shadow-sm mb-3">
            <div class="card-body pt-3 pb-2 bg-light d-flex justify-content-between align-items-center">
                <h4 class="fw-bold mb-0 text-guinda">Seguimiento</h4>
            </div>

            <div class="card-body p-3 p-md-4">
                <form action="{{ route('seguimiento.index') }}" method="GET" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-bold text-guinda2 small">Número de oficio:</label>
                            <input type="text" name="numero_oficio" class="form-control border-guinda"
                                placeholder="Buscar por número..." value="{{ $request->numero_oficio }}">
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="row g-2 align-items-end">
                                <div class="col-12 col-sm-6">
                                    <label class="form-label fw-bold text-guinda2 small">Fecha de recepción del:</label>
                                    <input type="date" name="fecha_recepcion" class="form-control border-guinda"
                                        value="{{ $request->fecha_recepcion }}">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label fw-bold text-guinda2 small">al:</label>
                                    <input type="date" name="fecha_recepcion_fin" class="form-control border-guinda"
                                        value="{{ $request->fecha_recepcion_fin }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label fw-bold text-guinda2 small">Dirigido a:</label>
                            <select name="dirigido_id" id="filtro_dirigido" class="form-select border-guinda">
                                <option value="0">Todas las unidades</option>
                                @foreach ($unidades as $id => $nombre)
                                    <option value="{{ $id }}"
                                        {{ $request->dirigido_id == $id ? 'selected' : '' }}>
                                        {{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-2 text-md-end mt-3 mt-md-0">
                            <button type="submit" class="btn btn-outline-guinda w-100 fw-bold">
                                Buscar
                            </button>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 d-flex flex-column flex-md-row align-items-md-center gap-2 gap-md-3">
                                <label class="form-label fw-bold text-guinda2 mb-0">Estatus:</label>

                                <div class="d-flex w-100 w-md-auto" style="overflow-x: auto; padding-bottom: 2px;">
                                    <div class="btn-group shadow-sm" role="group">
                                        <input type="radio" class="btn-check" name="estatus" value="Todos" id="st_todos"
                                            onchange="this.form.submit()"
                                            {{ $request->estatus == 'Todos' || !$request->filled('estatus') ? 'checked' : '' }}>
                                        <label
                                            class="btn btn-outline-secondary btn-sm px-3 py-2 badge-filtro-btn text-nowrap"
                                            for="st_todos">Todos</label>

                                        <input type="radio" class="btn-check" name="estatus" value="Turnado"
                                            id="st_turnado" onchange="this.form.submit()"
                                            {{ $request->estatus == 'Turnado' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-info btn-sm px-3 py-2 badge-filtro-btn text-nowrap"
                                            for="st_turnado">Turnados</label>

                                        <input type="radio" class="btn-check" name="estatus" value="Concluido"
                                            id="st_concluido" onchange="this.form.submit()"
                                            {{ $request->estatus == 'Concluido' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success btn-sm px-3 py-2 badge-filtro-btn text-nowrap"
                                            for="st_concluido">Concluidos</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card w-100 position-relative border-0 shadow-sm mt-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 1150px;">
                        <thead class="bg-guinda text-white">
                            <tr>
                                <th class="ps-4 py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Número de oficio</h6>
                                </th>
                                <th class="py-3 text-center text-nowrap">
                                    <h6 class="text-white form-label fw-bold small mb-0">Fecha de<br>recepción</h6>
                                </th>
                                <th class="py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Dirigido a</h6>
                                </th>
                                <th class="py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Solicitado por</h6>
                                </th>
                                <th class="py-3 text-center text-nowrap">
                                    <h6 class="text-white form-label fw-bold small mb-0">Fecha de<br>turno</h6>
                                </th>
                                <th class="py-3 text-left text-nowrap">
                                    <h6 class="text-white form-label fw-bold small mb-0">Sistema</h6>
                                </th>
                                <th class="py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Tipo de<br>requerimiento
                                    </h6>
                                </th>
                                <th class="py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Responsables</h6>
                                </th>
                                <th class="text-center py-3 text-nowrap">
                                    <h6 class="text-white form-label fw-bold small mb-0">Ver<br>oficio</h6>
                                </th>
                                <th class="text-center py-3 text-nowrap">
                                    <h6 class="text-white form-label fw-bold small mb-0">Solicitar<br>Respuesta</h6>
                                </th>
                                <th class="text-center py-3 text-nowrap">
                                    <h6 class="text-white form-label fw-bold small mb-0">Concluir</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($oficios as $oficio)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex flex-column">
                                            <a href="#timelineOficio{{ $oficio->id }}" data-bs-toggle="offcanvas"
                                                class="fw-bold text-left mb-1 fs-3 link-oficio-gris mb-1">
                                                {{ $oficio->numero_oficio }}
                                            </a>
                                            @php
                                                $badgeClass = match ($oficio->estatus) {
                                                    'Pendiente' => 'bg-warning text-dark',
                                                    'Turnado' => 'bg-info text-white',
                                                    'Concluido', 'Atendido' => 'bg-success text-white',
                                                    'Cancelado' => 'bg-danger text-white',
                                                    default => 'bg-secondary text-white',
                                                };
                                            @endphp
                                            <span
                                                class="badge {{ $badgeClass }} rounded-pill "style="width: fit-content; font-size: 0.75rem;">{{ $oficio->estatus }}</span>
                                        </div>
                                    </td>

                                    <td class="text-left text-wrap small">
                                        {{ $oficio->fecha_recepcion->format('d/m/Y') }}</td>
                                    <td class="small text-wrap text-uppercase">
                                        {{ $oficio->areaDirigido->nombre_unidad_administrativa ?? '-' }}</td>

                                    <td class="small text-left text-wrap">
                                        @if ($oficio->solicitantes->count() > 1)
                                            <div class="custom-hover-wrapper position-relative d-inline-block">
                                                <div class="text-uppercase" style="cursor: pointer;">
                                                    {{ mb_strtoupper($oficio->solicitantes->first()->nombre) }} <i
                                                        class="ti ti-arrow-down text-guinda fw-bold ms-1"></i>
                                                </div>
                                                <div
                                                    class="custom-hover-card shadow-lg border rounded bg-white text-start">
                                                    <div
                                                        class="bg-light px-3 py-2 border-bottom text-guinda fw-bold small rounded-top">
                                                        Solicitantes
                                                    </div>
                                                    <div class="px-3 py-2 text-left text-wrap small">
                                                        @foreach ($oficio->solicitantes as $sol)
                                                            <div class="text-uppercase mb-1">{{ $sol->nombre }}</div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($oficio->solicitantes->count() == 1)
                                            <div class="text-uppercase">
                                                {{ mb_strtoupper($oficio->solicitantes->first()->nombre) }}</div>
                                        @else
                                            <span class="fst-italic">Sin asignar</span>
                                        @endif
                                    </td>

                                    <td class="text-center text-wrap small">
                                        {{ !empty($oficio->fecha_turno) ? \Carbon\Carbon::parse($oficio->fecha_turno)->format('d/m/Y') : 'Pendiente' }}
                                    </td>
                                    <td class="text-left text-wrap small">
                                        {{ $oficio->sistema->sigla_sistema ?? 'N/A' }}</td>
                                    <td class="small text-wrap">
                                        {{ $oficio->tipoRequerimiento->nombre ?? 'Cambios menores' }}</td>

                                    <td class="small text-wrap">
                                        @if ($oficio->responsablesOficios->count() > 1)
                                            <div class="custom-hover-wrapper position-relative d-inline-block">
                                                <div class="d-flex align-items-center" style="cursor: pointer;">
                                                    {{ mb_strtoupper($oficio->responsablesOficios->first()->responsable->nombre ?? 'USUARIO') }}
                                                    <i class="ti ti-arrow-down fw-bold text-guinda ms-1"></i>
                                                </div>
                                                <div
                                                    class="custom-hover-card shadow-lg border rounded bg-white text-start">
                                                    <div
                                                        class="bg-light px-3 py-2 border-bottom text-guinda fw-bold small rounded-top">
                                                        Responsables
                                                    </div>
                                                    <div class="px-3 py-2 text-wrap small fw-normal">
                                                        @foreach ($oficio->responsablesOficios as $resp)
                                                            <div class="mb-1 text-uppercase">
                                                                {{ $resp->responsable->nombre ?? 'Usuario' }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($oficio->responsablesOficios->count() == 1)
                                            <div class="d-flex text-wrap align-items-center">
                                                {{ mb_strtoupper($oficio->responsablesOficios->first()->responsable->nombre ?? 'USUARIO') }}
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic fw-normal">Sin asignar</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ $oficio->url_oficio }}" target="_blank" class="text-guinda fs-4"
                                            title="Ver documento PDF">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </td>

                                    <td class="text-center">
                                        @if($oficio->estatus === 'Concluido')
                                            <a href="{{ route('seguimiento.notificar', $oficio->id) }}" 
                                               class="btn border-0 bg-transparent text-guinda fs-4" 
                                               title="Notificar para generar respuesta">
                                                <i class="ti ti-mail"></i>
                                            </a>
                                        @else
                                            <button class="btn border-0 bg-transparent text-muted fs-4" disabled title="El oficio debe estar Concluido">
                                                <i class="ti ti-mail-off"></i>
                                            </button>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($oficio->estatus == 'Turnado')
                                            <button class="btn border-0 bg-transparent text-guinda fs-4"
                                                data-bs-toggle="modal" data-bs-target="#modalConcluir{{ $oficio->id }}"
                                                title="Concluir oficio">
                                                <i class="ti ti-file-plus"></i>
                                            </button>
                                        @else
                                            <button class="btn border-0 bg-transparent text-guinda fs-4"
                                                data-bs-toggle="modal" data-bs-target="#modalConcluir{{ $oficio->id }}"
                                                title="Editar conclusión">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <i class="ti ti-timeline fs-1 text-muted d-block mb-2"></i>
                                        <span class="text-muted">No hay oficios en seguimiento.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3 d-flex justify-content-center justify-content-md-end">
                    {!! $oficios->appends($request->all())->links() !!}
                </div>
            </div>
        </div>
    </div>

    @foreach ($oficios as $oficio)
        <div class="offcanvas offcanvas-end shadow-lg" tabindex="-1" id="timelineOficio{{ $oficio->id }}"
            style="width: 450px;">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold text-guinda">Seguimientos</h5>
                @if ($oficio->responsablesOficios->count() > 0)
                    <button type="button"
                        class="btn btn-guinda rounded-circle shadow-sm ms-auto me-3 d-flex justify-content-center align-items-center"
                        style="width: 35px; height: 35px;" data-bs-toggle="modal"
                        data-bs-target="#modalAddAvance{{ $oficio->responsablesOficios->first()->id }}"
                        title="Agregar avance">
                        <i class="ti ti-plus"></i>
                    </button>
                @endif
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>

            <div class="offcanvas-body p-0 bg-light">
                <div class="p-4 bg-white border-bottom">
                    <p class="text-muted small mb-1">Descripción del requerimiento:</p>
                    <p class="text-dark  small mb-3">{{ $oficio->descripción_oficio }}</p>
                    <p class="text-muted small mb-1">Sistema:</p>
                    <p class="text-dark  small mb-3">{{ $oficio->sistema->nombre_sistema ?? 'N/A' }}</p>
                    <p class="text-muted small mb-1">Responsables:</p>
                    @foreach ($oficio->responsablesOficios as $resp)
                        <p class="text-dark small mb-0"><i class="ti ti-check text-success me-1"></i>
                            {{ $resp->responsable->nombre ?? 'Usuario' }}</p>
                    @endforeach
                </div>

                @php
                    $todosSeguimientos = collect();
                    foreach ($oficio->responsablesOficios as $resp) {
                        $todosSeguimientos = $todosSeguimientos->merge($resp->seguimientos);
                    }
                    $todosSeguimientos = $todosSeguimientos->sortByDesc('fecha_creacion');

                    $agrupados = [
                        'Pendiente' => $todosSeguimientos->where('estatus', 'Pendiente'),
                        'En Desarrollo' => $todosSeguimientos->where('estatus', 'En Desarrollo'),
                        'En validación' => $todosSeguimientos->where('estatus', 'En validación'),
                        'Publicado' => $todosSeguimientos->where('estatus', 'Publicado'),
                    ];

                    $ultimoRegistro = $todosSeguimientos->first();
                    $nivelActual = 0;

                    if ($ultimoRegistro) {
                        $nivelActual = match ($ultimoRegistro->estatus) {
                            'Pendiente' => 1,
                            'En Desarrollo' => 2,
                            'En validación' => 3,
                            'Publicado' => 4,
                            default => 1,
                        };
                    }

                    $focoActual = $nivelActual == 0 ? 1 : $nivelActual;
                @endphp

                <div class="px-3 py-4 bg-white">
                    <div class="stepper-wrapper">
                        <div class="stepper-item {{ $nivelActual >= 1 ? 'completed' : '' }} {{ $focoActual == 1 ? 'active' : '' }}"
                            onclick="showTimelineDetails('{{ $oficio->id }}', 'Pendiente', 0)">
                            <div class="step-counter"><i class="ti ti-clipboard-list"></i></div>
                            <div class="step-name">Pendiente</div>
                        </div>
                        <div class="stepper-item {{ $nivelActual >= 2 ? 'completed' : '' }} {{ $focoActual == 2 ? 'active' : '' }}"
                            onclick="showTimelineDetails('{{ $oficio->id }}', 'Desarrollo', 1)">
                            <div class="step-counter"><i class="ti ti-code"></i></div>
                            <div class="step-name">Desarrollo</div>
                        </div>
                        <div class="stepper-item {{ $nivelActual >= 3 ? 'completed' : '' }} {{ $focoActual == 3 ? 'active' : '' }}"
                            onclick="showTimelineDetails('{{ $oficio->id }}', 'Validacion', 2)">
                            <div class="step-counter"><i class="ti ti-shield-check"></i></div>
                            <div class="step-name">Validación</div>
                        </div>
                        <div class="stepper-item {{ $nivelActual >= 4 ? 'completed' : '' }} {{ $focoActual == 4 ? 'active' : '' }}"
                            onclick="showTimelineDetails('{{ $oficio->id }}', 'Publicado', 3)">
                            <div class="step-counter"><i class="ti ti-rocket"></i></div>
                            <div class="step-name">Publicado</div>
                        </div>
                    </div>
                </div>

                <div class="p-4 pt-2">
                    <h6 class="fw-bold text-guinda mb-3" id="titleDetails{{ $oficio->id }}">Historial de Detalles</h6>

                    <div class="timeline-details-panel" id="panel-Pendiente-{{ $oficio->id }}"
                        style="display: {{ $focoActual == 1 ? 'block' : 'none' }};">
                        @if ($agrupados['Pendiente']->count() > 0)
                            @foreach ($agrupados['Pendiente'] as $seg)
                                <div class="card border-0 shadow-sm mb-2 rounded-3">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span
                                                class="badge bg-warning-subtle text-warning rounded-pill">Pendiente</span>
                                            <small
                                                class="text-muted fw-bold">{{ $seg->fecha_seguimiento->format('d/m/Y') }}</small>
                                        </div>
                                        <p class="mb-0 small text-dark">{{ $seg->observaciones }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted small text-center fst-italic">No hay registros en esta etapa.</p>
                        @endif
                    </div>

                    <div class="timeline-details-panel" id="panel-Desarrollo-{{ $oficio->id }}"
                        style="display: {{ $focoActual == 2 ? 'block' : 'none' }};">
                        @if ($agrupados['En Desarrollo']->count() > 0)
                            @foreach ($agrupados['En Desarrollo'] as $seg)
                                <div class="card border-0 shadow-sm mb-2 rounded-3">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-primary-subtle text-primary rounded-pill">En
                                                Desarrollo</span>
                                            <small
                                                class="text-muted fw-bold">{{ $seg->fecha_seguimiento->format('d/m/Y') }}</small>
                                        </div>
                                        <p class="mb-0 small text-dark">{{ $seg->observaciones }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted small text-center fst-italic">No hay registros en esta etapa.</p>
                        @endif
                    </div>

                    <div class="timeline-details-panel" id="panel-Validacion-{{ $oficio->id }}"
                        style="display: {{ $focoActual == 3 ? 'block' : 'none' }};">
                        @if ($agrupados['En validación']->count() > 0)
                            @foreach ($agrupados['En validación'] as $seg)
                                <div class="card border-0 shadow-sm mb-2 rounded-3">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-info-subtle text-info rounded-pill">En Validación</span>
                                            <small
                                                class="text-muted fw-bold">{{ $seg->fecha_seguimiento->format('d/m/Y') }}</small>
                                        </div>
                                        <p class="mb-0 small text-dark">{{ $seg->observaciones }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted small text-center fst-italic">No hay registros en esta etapa.</p>
                        @endif
                    </div>

                    <div class="timeline-details-panel" id="panel-Publicado-{{ $oficio->id }}"
                        style="display: {{ $focoActual == 4 ? 'block' : 'none' }};">
                        @if ($agrupados['Publicado']->count() > 0)
                            @foreach ($agrupados['Publicado'] as $seg)
                                <div class="card border-0 shadow-sm mb-2 rounded-3">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span
                                                class="badge bg-success-subtle text-success rounded-pill">Publicado</span>
                                            <small
                                                class="text-muted fw-bold">{{ $seg->fecha_seguimiento->format('d/m/Y') }}</small>
                                        </div>
                                        <p class="mb-0 small text-dark">{{ $seg->observaciones }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted small text-center fst-italic">No hay registros en esta etapa.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ($oficio->responsablesOficios->count() > 0)
            @php
                $responsableBaseId = $oficio->responsablesOficios->first()->id;
                $modalAddId = 'modalAddAvance' . $responsableBaseId;
            @endphp
            <div class="modal fade" id="{{ $modalAddId }}" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-light border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold text-guinda">Nuevo Seguimiento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4 bg-white">
                            <form action="{{ route('seguimiento.avance.store', $responsableBaseId) }}" method="POST"
                                novalidate>
                                @csrf
                                <input type="hidden" name="error_modal_id" value="{{ $modalAddId }}">
                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-bold text-guinda2 small">Fecha de seguimiento:</label>
                                        <input type="date" class="form-control bg-light text-muted border-guinda"
                                            value="{{ date('Y-m-d') }}" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold text-guinda2 small">Estatus del
                                            seguimiento:</label>
                                        <select name="estatus" class="form-select bg-white border-guinda" required>
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="En Desarrollo">En Desarrollo</option>
                                            <option value="En validación">En Validación</option>
                                            <option value="Publicado">Publicado</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-guinda2 small">Observaciones:</label>
                                    @php $hasObsError = old('error_modal_id') == $modalAddId && $errors->has('observaciones'); @endphp
                                    <textarea name="observaciones" class="form-control bg-white border-guinda {{ $hasObsError ? 'is-invalid' : '' }}"
                                        rows="3" required>{{ old('observaciones') }}</textarea>
                                    @if ($hasObsError)
                                        <span
                                            class="invalid-feedback fw-bold mt-1">{{ $errors->first('observaciones') }}</span>
                                    @endif
                                </div>

                                <div class="d-flex align-items-center pt-3 border-top">
                                    <button type="submit"
                                        class="btn btn-guardar-modal rounded-pill px-4 py-2 me-3 shadow-sm">Guardar</button>
                                    <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @php $modalConcluirId = 'modalConcluir' . $oficio->id; @endphp
        <div class="modal fade" id="{{ $modalConcluirId }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold text-guinda">
                            {{ $oficio->estatus == 'Turnado' ? 'Concluir oficio:' : 'Editar oficio:' }} <span
                                class="text-guinda2">{{ $oficio->numero_oficio }}</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form action="{{ route('seguimiento.concluir', $oficio->id) }}" method="POST"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            <input type="hidden" name="error_modal_id" value="{{ $modalConcluirId }}">

                            <div class="mb-3">
                                <label class="form-label fw-bold text-guinda2 small">Fecha de conclusión:<span
                                        class="text-danger">*</span></label>
                                @php $hasFechaError = old('error_modal_id') == $modalConcluirId && $errors->has('fecha_conclusion'); @endphp
                                <input type="date" name="fecha_conclusion"
                                    class="form-control border-guinda {{ $hasFechaError ? 'is-invalid' : '' }}"
                                    value="{{ old('fecha_conclusion', $oficio->fecha_conclusion ? $oficio->fecha_conclusion->format('Y-m-d') : date('Y-m-d')) }}"
                                    required>
                                @if ($hasFechaError)
                                    <span
                                        class="invalid-feedback fw-bold mt-1">{{ $errors->first('fecha_conclusion') }}</span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-guinda2 small">Soporte documental:<span
                                        class="text-danger">*</span></label>
                                @if ($oficio->soporte_documental)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $oficio->soporte_documental) }}" target="_blank"
                                            class="badge bg-success text-decoration-none p-2 fs-6">
                                            <i class="ti ti-file-check me-1"></i> Ver documento guardado
                                        </a>
                                    </div>
                                    <small class="text-muted d-block mb-1">Si sube un nuevo archivo, reemplazará al
                                        actual.</small>
                                @endif
                                @php $hasDocError = old('error_modal_id') == $modalConcluirId && $errors->has('soporte_documental'); @endphp
                                <input type="file" name="soporte_documental"
                                    class="form-control border-guinda {{ $hasDocError ? 'is-invalid' : '' }}"
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                    {{ $oficio->soporte_documental ? '' : 'required' }}>
                                @if ($hasDocError)
                                    <span
                                        class="invalid-feedback fw-bold mt-1">{{ $errors->first('soporte_documental') }}</span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-guinda2 small mb-2">
                                    Texto propuesta para la respuesta:<span class="text-danger">*</span>
                                </label>

                                @php $hasPropError = old('error_modal_id') == $modalConcluirId && $errors->has('propuesta_respuesta'); @endphp

                                <textarea name="propuesta_respuesta"
                                    class="form-control border-guinda rounded-3 {{ $hasPropError ? 'is-invalid' : '' }}" rows="5" required>{{ old('propuesta_respuesta', $oficio->propuesta_respuesta) }}</textarea>

                                @if ($hasPropError)
                                    <span
                                        class="invalid-feedback fw-bold mt-1">{{ $errors->first('propuesta_respuesta') }}</span>
                                @endif
                            </div>
                            <div class="form-check mt-4 mb-3 d-flex align-items-center gap-2">
                                <input class="form-check-input mt-0 border-guinda" type="checkbox"
                                    name="alcance_otro_oficio" id="checkAlcance{{ $oficio->id }}" value="X"
                                    onchange="toggleSelectOficios('{{ $oficio->id }}')"
                                    {{ $oficio->alcance_otro_oficio == 'X' ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-guinda2 small"
                                    for="checkAlcance{{ $oficio->id }}">
                                    En alcance a otro oficio:
                                </label>
                            </div>

                            <div id="divSelectOficio{{ $oficio->id }}"
                                class="mb-4 bg-white border border-guinda rounded p-3"
                                style="display: {{ $oficio->alcance_otro_oficio == 'X' ? 'block' : 'none' }};">
                                <label class="form-label text-guinda2 small fw-bold">Buscar y agregar oficios:</label>

                                <div class="d-flex gap-2 mb-3">
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <select id="select_vinculado_{{ $oficio->id }}"
                                            class="form-select border-guinda">
                                            <option value="">Seleccione el oficio...</option>
                                            @foreach ($listaOficios as $id_of => $num_of)
                                                <option value="{{ $id_of }}">{{ $num_of }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button"
                                        class="btn btn-guinda text-nowrap rounded-pill px-3 border border-guinda"
                                        onclick="agregarOficioVinculado('{{ $oficio->id }}')">
                                        Agregar
                                    </button>
                                </div>

                                <label class="form-label text-guinda2 small fw-bold mb-2">Oficios vinculados a este
                                    requerimiento:</label>
                                <div id="lista_vinculados_{{ $oficio->id }}" class="d-flex flex-column gap-2">
                                    @foreach ($oficio->oficiosVinculados as $vinculado)
                                        <div class="d-flex justify-content-between align-items-center bg-light border border-secondary-subtle rounded px-3 py-2 shadow-sm"
                                            id="vinc_{{ $oficio->id }}_{{ $vinculado->id }}">
                                            <span class="text-guinda fw-semibold small"><i class="ti ti-link me-1"></i>
                                                {{ $vinculado->numero_oficio }}</span>
                                            <input type="hidden" name="oficios_vinculados[]"
                                                value="{{ $vinculado->id }}">
                                            <button type="button"
                                                class="btn btn-sm btn-white text-danger border rounded-circle shadow-sm"
                                                onclick="removerVinculado('{{ $oficio->id }}', '{{ $vinculado->id }}')">
                                                <i class="ti ti-x"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-flex align-items-center pt-3 mt-4 border-top">
                                <button type="submit"
                                    class="btn btn-guardar-modal rounded-pill px-4 py-2 me-3 shadow-sm">
                                    Guardar
                                </button>
                                <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <style>
        /* Estilos base de la paleta Guinda */
        .border-guinda {
            border-color: #9D2449 !important;
        }

        .btn-guinda-light {
            background-color: #F8E8EC;
            color: #9D2449;
            border: none;
            font-weight: bold;
        }

        .btn-guinda-light:hover {
            background-color: #9D2449;
            color: white;
        }

        .link-oficio-guinda {
            color: grey;
            text-decoration: none;
            transition: 0.2s;
        }

        .link-oficio-guinda:hover {
            text-decoration: underline;
            color: #9D2449;
        }

        /* CSS BOTONES MODAL GUARDAR/CANCELAR */
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

        /* CSS TARJETAS FLOTANTES (TOOLTIPS HOVER) */
        .custom-hover-wrapper .custom-hover-card {
            visibility: hidden;
            opacity: 0;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1050;
            min-width: 280px;
            transition: all 0.2s ease-in-out;
            margin-top: 10px;
        }

        .custom-hover-wrapper:hover .custom-hover-card {
            visibility: visible;
            opacity: 1;
            margin-top: 5px;
        }

        /* CSS DEL STEPPER HORIZONTAL */
        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            position: relative;
        }

        .stepper-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .stepper-item:hover {
            transform: scale(1.05);
        }

        .stepper-item::before {
            position: absolute;
            content: "";
            border-bottom: 3px solid #e9ecef;
            width: 100%;
            top: 20px;
            left: -50%;
            z-index: 2;
        }

        .stepper-item:first-child::before {
            content: none;
        }

        .stepper-item .step-counter {
            position: relative;
            z-index: 5;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            margin-bottom: 8px;
            font-size: 1.2rem;
            transition: 0.3s ease;
        }

        .stepper-item .step-name {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6c757d;
            text-align: center;
        }

        /* ESTADO COMPLETADO */
        .stepper-item.completed .step-counter {
            background-color: #9D2449;
            color: white;
        }

        .stepper-item.completed::before {
            border-color: #9D2449;
        }

        .stepper-item.completed .step-name {
            color: #9D2449;
        }

        /* ESTADO ACTIVO */
        .stepper-item.active .step-counter {
            box-shadow: 0 0 0 4px rgba(157, 36, 73, 0.25);
        }

        .stepper-item.active:not(.completed) .step-counter {
            background-color: #F8E8EC;
            color: #9D2449;
            border: 2px solid #9D2449;
        }

        .stepper-item.active .step-name {
            color: #9D2449;
            font-weight: bold;
        }

        /* Colores para badges */
        .bg-primary-subtle {
            background-color: #e7f1ff !important;
        }

        .bg-info-subtle {
            background-color: #e4f7fb !important;
        }

        .bg-warning-subtle {
            background-color: #fef7e0 !important;
        }

        .bg-success-subtle {
            background-color: #e6f4ea !important;
        }
    </style>

    <script>
        // -----------------------------------------------------------------
        // MAGIA PARA REABRIR EL MODAL QUE TIENE ERRORES DE VALIDACIÓN
        // -----------------------------------------------------------------
        document.addEventListener("DOMContentLoaded", function() {
            @if (old('error_modal_id'))
                var errorModalId = "{{ old('error_modal_id') }}";
                var modalElement = document.getElementById(errorModalId);
                if (modalElement) {
                    var myModal = new bootstrap.Modal(modalElement);
                    myModal.show();
                }
            @endif
        });
        var todosLosModales = document.querySelectorAll('.modal');
        todosLosModales.forEach(function(modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                var form = this.querySelector('form');
                if (form) {
                    var inputsInvalidos = form.querySelectorAll('.is-invalid');
                    inputsInvalidos.forEach(input => input.classList.remove('is-invalid'));

                    var mensajesError = form.querySelectorAll('.invalid-feedback');
                    mensajesError.forEach(msg => msg.remove());

                    form.reset();
                }
            });
        });

        // 1. SCRIPT DEL BUSCADOR JS 
        function convertirSelectABuscador(idSelect) {
            const originalSelect = document.getElementById(idSelect);
            if (!originalSelect) return;

            const wrapperPrevio = originalSelect.parentNode.querySelector('.searchable-dropdown-wrapper');
            if (wrapperPrevio) wrapperPrevio.remove();

            const wrapper = document.createElement('div');
            wrapper.className = 'searchable-dropdown-wrapper position-relative w-100';

            const trigger = document.createElement('button');
            trigger.className = 'form-select searchable-trigger border-guinda text-start text-truncate bg-white w-100';
            trigger.type = 'button';

            const selectedOption = originalSelect.options[originalSelect.selectedIndex];
            trigger.textContent = selectedOption && selectedOption.value !== "" ? selectedOption.text :
                'Seleccione una opción...';

            const menu = document.createElement('div');
            menu.className = 'searchable-menu bg-white border rounded shadow-sm p-2 w-100';
            menu.style.position = 'absolute';
            menu.style.top = '100%';
            menu.style.left = '0';
            menu.style.zIndex = '1050';
            menu.style.display = 'none';

            const inputSearch = document.createElement('input');
            inputSearch.className = 'form-control mb-2 border-guinda';
            inputSearch.type = 'text';
            inputSearch.placeholder = 'Buscar...';
            inputSearch.onclick = function(e) {
                e.stopPropagation();
            };

            const optionsList = document.createElement('div');
            optionsList.className = 'searchable-options';
            optionsList.style.maxHeight = '200px';
            optionsList.style.overflowY = 'auto';

            function poblarOpciones() {
                optionsList.innerHTML = '';
                Array.from(originalSelect.options).forEach(option => {
                    if (option.value === "") return;

                    const item = document.createElement('div');
                    item.className = 'searchable-option p-2 rounded text-secondary small';
                    item.style.cursor = 'pointer';
                    item.textContent = option.text;

                    item.addEventListener('mouseover', () => {
                        item.style.backgroundColor = '#F8E8EC';
                        item.style.color = '#9D2449';
                    });
                    item.addEventListener('mouseout', () => {
                        item.style.backgroundColor = 'transparent';
                        item.style.color = '#6c757d';
                    });

                    item.addEventListener('click', () => {
                        originalSelect.value = option.value;
                        trigger.textContent = option.text;
                        menu.style.display = 'none';
                        inputSearch.value = '';
                        filtrarOpciones('');
                        originalSelect.dispatchEvent(new Event('change'));
                    });
                    optionsList.appendChild(item);
                });
            }
            poblarOpciones();

            function filtrarOpciones(texto) {
                const items = optionsList.querySelectorAll('.searchable-option');
                const filtro = texto.toLowerCase();
                items.forEach(item => {
                    const coincide = item.textContent.toLowerCase().includes(filtro);
                    item.style.display = coincide ? 'block' : 'none';
                });
            }

            inputSearch.addEventListener('keyup', (e) => filtrarOpciones(e.target.value));

            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                const isShowing = menu.style.display === 'block';
                document.querySelectorAll('.searchable-menu').forEach(m => m.style.display = 'none');

                if (!isShowing) {
                    menu.style.display = 'block';
                    setTimeout(() => inputSearch.focus(), 100);
                }
            });

            document.addEventListener('click', (e) => {
                if (!wrapper.contains(e.target)) menu.style.display = 'none';
            });

            menu.appendChild(inputSearch);
            menu.appendChild(optionsList);
            wrapper.appendChild(trigger);
            wrapper.appendChild(menu);

            originalSelect.parentNode.insertBefore(wrapper, originalSelect.nextSibling);
            originalSelect.style.display = 'none';
        }

        document.addEventListener("DOMContentLoaded", function() {
            convertirSelectABuscador('filtro_dirigido');
            @foreach ($oficios as $oficio)
                convertirSelectABuscador('select_vinculado_{{ $oficio->id }}');
            @endforeach
        });

        // 2. SCRIPT DE LA LÍNEA DE TIEMPO
        function showTimelineDetails(oficioId, estadoClicked, idx) {
            const paneles = document.querySelectorAll(`[id^="panel-"][id$="-${oficioId}"]`);
            paneles.forEach(panel => panel.style.display = 'none');

            const targetPanel = document.getElementById(`panel-${estadoClicked}-${oficioId}`);
            if (targetPanel) targetPanel.style.display = 'block';

            const offcanvas = document.getElementById(`timelineOficio${oficioId}`);
            if (!offcanvas) return;

            const items = offcanvas.querySelectorAll('.stepper-item');
            items.forEach(item => item.classList.remove('active'));

            if (items[idx]) {
                items[idx].classList.add('active');
            }
        }

        // 3. SCRIPT DEL CHECKBOX Y MÚLTIPLES OFICIOS
        function toggleSelectOficios(oficioId) {
            const checkbox = document.getElementById('checkAlcance' + oficioId);
            const divSelect = document.getElementById('divSelectOficio' + oficioId);
            if (checkbox && divSelect) {
                divSelect.style.display = checkbox.checked ? 'block' : 'none';
            }
        }

        function agregarOficioVinculado(oficioId) {
            const select = document.getElementById('select_vinculado_' + oficioId);
            const value = select.value;

            let text = select.options[select.selectedIndex]?.text;
            const wrapper = select.nextElementSibling;

            if (!value) {
                alert('Por favor, busque y seleccione un oficio de la lista primero.');
                return;
            }

            const lista = document.getElementById('lista_vinculados_' + oficioId);

            if (lista.querySelector(`input[value="${value}"]`)) {
                alert('Este oficio ya se encuentra agregado en la lista.');
                return;
            }

            const div = document.createElement('div');
            div.className =
                'd-flex justify-content-between align-items-center bg-light border border-secondary-subtle rounded px-3 py-2 shadow-sm';
            div.id = `vinc_${oficioId}_${value}`;

            div.innerHTML = `
            <span class="text-guinda fw-semibold small"><i class="ti ti-link me-1"></i> ${text}</span>
            <input type="hidden" name="oficios_vinculados[]" value="${value}">
            <button type="button" class="btn btn-sm btn-white text-danger border rounded-circle shadow-sm" onclick="removerVinculado('${oficioId}', '${value}')">
                <i class="ti ti-x"></i>
            </button>
        `;

            lista.appendChild(div);

            select.value = '';
            if (wrapper && wrapper.classList.contains('searchable-dropdown-wrapper')) {
                const trigger = wrapper.querySelector('.searchable-trigger');
                if (trigger) trigger.textContent = 'Seleccione el oficio...';
            }
        }

        function removerVinculado(oficioId, vinculadoId) {
            const div = document.getElementById(`vinc_${oficioId}_${vinculadoId}`);
            if (div) div.remove();
        }
    </script>
@endsection