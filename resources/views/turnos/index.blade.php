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
            <div class="card-body pt-2 py-3 bg-light">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h4 class="fw-bold mb-0 text-guinda">Turno</h4>
                    </div>
                </div>
            </div>

            <div class="card-body p-3 p-md-4">
                <form action="{{ route('turno.index') }}" method="GET" id="filterForm">
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

                        {{-- Condición usando tu estándar de roles --}}
                        @if(Auth::user()->rol != 'Titular de área')
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
                        @endif

                        <div class="col-12 col-md-2 text-md-end mt-3 mt-md-0">
                            <button type="submit" class="btn btn-outline-guinda w-100 fw-bold">
                                Buscar
                            </button>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 d-flex flex-column flex-md-row align-items-md-center gap-2 gap-md-3">
                            <label class="form-label fw-bold text-guinda2 mb-0">Estatus:</label>

                            <div class="d-flex w-100 w-md-auto" style="overflow-x: auto; padding-bottom: 2px;">
                                <div class="btn-group shadow-sm" role="group">
                                    <input type="radio" class="btn-check" name="estatus" value="Todos" id="st_todos"
                                        onchange="this.form.submit()"
                                        {{ $request->estatus == 'Todos' || !$request->filled('estatus') ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary btn-sm px-3 py-2 badge-filtro-btn text-nowrap"
                                        for="st_todos">Todos</label>

                                    <input type="radio" class="btn-check" name="estatus" value="Pendiente"
                                        id="st_pendiente" onchange="this.form.submit()"
                                        {{ $request->estatus == 'Pendiente' ? 'checked' : '' }}>
                                    <label
                                        class="btn btn-outline-warning btn-sm px-3 py-2 text-whit badge-filtro-btn text-nowrap"
                                        for="st_pendiente">Pendientes</label>

                                    <input type="radio" class="btn-check" name="estatus" value="Turnado" id="st_turnado"
                                        onchange="this.form.submit()" {{ $request->estatus == 'Turnado' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-info btn-sm px-3 py-2 badge-filtro-btn text-nowrap"
                                        for="st_turnado">Turnados</label>

                                    <input type="radio" class="btn-check" name="estatus" value="Eliminado"
                                        id="st_eliminado" onchange="this.form.submit()"
                                        {{ $request->estatus == 'Eliminado' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-gold btn-sm px-3 py-2 badge-filtro-btn text-nowrap"
                                        for="st_eliminado">Eliminados</label>

                                    <input type="radio" class="btn-check" name="estatus" value="Cancelado"
                                        id="st_cancelado" onchange="this.form.submit()"
                                        {{ $request->estatus == 'Cancelado' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger btn-sm px-3 py-2 badge-filtro-btn text-nowrap"
                                        for="st_cancelado">Cancelados</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card w-100 position-relative overflow-hidden border-0 shadow-sm mt-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" style="min-width: 1000px;">
                        <thead class="bg-guinda text-white">
                            <tr>
                                <th class="ps-4 py-3 text-nowrap">
                                    <h6 class="text-white form-label fw-bold small mb-0">Número de oficio</h6>
                                </th>
                                <th class="py-3 text-nowrap">
                                    <h6 class="text-white form-label text-center fw-bold small mb-0">Fecha de recepción</h6>
                                </th>
                                
                                {{-- Condición para ocultar la cabecera usando tu estándar --}}
                                @if(Auth::user()->rol != 'Titular de área')
                                    <th class="py-3 text-nowrap">
                                        <h6 class="text-white text-left form-label fw-bold small mb-0">Dirigido a</h6>
                                    </th>
                                @endif

                                <th class="py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Solicitado por</h6>
                                </th>
                                <th class="py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Fecha de turno</h6>
                                </th>
                                <th class="py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Sistema</h6>
                                </th>
                                <th class="py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Tipo de requerimiento
                                    </h6>
                                </th>
                                <th class="py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Responsables</h6>
                                </th>
                                <th class="text-center py-3 text-nowrap">
                                    <h6 class="text-white form-label fw-bold small mb-0">Notificar</h6>
                                </th>
                                <th class="text-center py-3 text-nowrap">
                                    <h6 class="text-white form-label fw-bold small mb-0">Ver PDF</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($oficios as $oficio)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('turno.edit', $oficio->id) }}"
                                                class="fw-bold mb-1 fs-3 link-oficio-gris text-left">
                                                {{ $oficio->numero_oficio }}
                                            </a>
                                            @php
                                                $badgeClass = match ($oficio->estatus) {
                                                    'Pendiente' => 'bg-warning text-white',
                                                    'Turnado' => 'bg-info text-white',
                                                    'Concluido', 'Atendido' => 'bg-success text-white',
                                                    'Cancelado' => 'bg-danger text-white',
                                                    default => 'bg-secondary text-white',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }} rounded-pill "
                                                style="width: fit-content; font-size: 0.75rem;">
                                                {{ $oficio->estatus }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="small text-wrap text-center">
                                            {{ optional($oficio->fecha_recepcion)->format('d/m/Y') ?? 'N/A' }}
                                        </div>
                                    </td>

                                    {{-- Condición para ocultar la celda usando tu estándar --}}
                                    @if(Auth::user()->rol != 'Titular de área')
                                        <td>
                                            <div class="small text-wrap text-left"
                                                title="{{ optional($oficio->areaDirigido)->nombre_unidad_administrativa }}">
                                                {{ optional($oficio->areaDirigido)->nombre_unidad_administrativa ?? 'N/A' }}
                                            </div>
                                        </td>
                                    @endif

                                    <td class="small text-wrap text-left">
                                        @if (isset($oficio->solicitantes) && $oficio->solicitantes->count() > 1)
                                            <div class="custom-hover-wrapper text-left position-relative d-inline-block">
                                                <div class="text-uppercase" style="cursor: pointer;">
                                                    {{ mb_strtoupper($oficio->solicitantes->first()->nombre) }}
                                                    <i class="ti ti-arrow-down text-guinda fw-bold ms-1"></i>
                                                </div>
                                                <div
                                                    class="custom-hover-card shadow-lg border rounded bg-white text-start">
                                                    <div
                                                        class="bg-light px-3 py-2 border-bottom text-guinda fw-bold small rounded-top">
                                                        Solicitantes
                                                    </div>
                                                    <div class="px-3 py-2 text-wrap text-left small">
                                                        @foreach ($oficio->solicitantes as $sol)
                                                            <div class="text-uppercase mb-1">• {{ $sol->nombre }}</div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif (isset($oficio->solicitantes) && $oficio->solicitantes->count() == 1)
                                            <div class="text-uppercase">
                                                {{ mb_strtoupper($oficio->solicitantes->first()->nombre) }}
                                            </div>
                                        @else
                                            <span class="text-muted small fst-italic">Sin asignar</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="small text-wrap">
                                            {{ !empty($oficio->fecha_turno) ? \Carbon\Carbon::parse($oficio->fecha_turno)->format('d/m/Y') : 'Pendiente' }}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="small text-wrap mb-1"
                                            title="{{ optional($oficio->sistema)->sigla_sistema }}">
                                            {{ optional($oficio->sistema)->sigla_sistema ?? 'Sin asignar' }}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="small text-wrap mb-1"
                                            title="{{ optional($oficio->tipoRequerimiento)->tipo_requerimiento }}">
                                            {{ optional($oficio->tipoRequerimiento)->tipo_requerimiento ?? 'Sin asignar' }}
                                        </div>
                                    </td>

                                    <td class="small text-wrap">
                                        @if (isset($oficio->responsablesOficios) && $oficio->responsablesOficios->count() > 1)
                                            <div class="custom-hover-wrapper position-relative d-inline-block">
                                                <div class="text-uppercase" style="cursor: pointer;">
                                                    {{ mb_strtoupper(optional($oficio->responsablesOficios->first()->responsable)->nombre ?? 'Desconocido') }}
                                                    <i class="ti ti-arrow-down text-guinda fw-bold ms-1"></i>
                                                </div>
                                                <div
                                                    class="custom-hover-card shadow-lg border rounded bg-white text-start">
                                                    <div
                                                        class="bg-light px-3 py-2 border-bottom text-guinda fw-bold small rounded-top">
                                                        Responsables
                                                    </div>
                                                    <div class="px-3 py-2 text-wrap small">
                                                        @foreach ($oficio->responsablesOficios as $ro)
                                                            <div class="text-uppercase mb-1">•
                                                                {{ optional($ro->responsable)->nombre ?? 'Desconocido' }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif (isset($oficio->responsablesOficios) && $oficio->responsablesOficios->count() == 1)
                                            <div class="text-uppercase">
                                                {{ mb_strtoupper(optional($oficio->responsablesOficios->first()->responsable)->nombre ?? 'Desconocido') }}
                                            </div>
                                        @else
                                            <span class="text-muted text-left small fst-italic">Sin responsables</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if (isset($oficio->responsablesOficios) && $oficio->responsablesOficios->count() > 0)
                                            <a href="{{ route('turno.notificar', $oficio->id) }}"
                                                class="btn btn-outline-secondary border-0 bg-transparent text-primary"
                                                title="Notificar por correo a responsables">
                                                <i class="ti ti-mail fs-5"></i>
                                            </a>
                                        @else
                                            <span class="text-muted"><i class="ti ti-mail-off fs-5"
                                                    title="Sin responsables para notificar"></i></span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ $oficio->url_oficio }}" target="_blank"
                                            class="btn btn-outline-guinda border-0 bg-transparent"
                                            title="Ver documento PDF">
                                            <i class="ti ti-eye fs-5"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <i class="ti ti-files fs-1 text-muted mb-2 d-block"></i>
                                        <div class="text-muted">No se encontraron turnos registrados.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 px-3 px-md-4 pb-3 d-flex justify-content-center justify-content-md-end">
                    {!! $oficios->appends($request->all())->links() !!}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof convertirSelectABuscador === 'function') {
                if (document.getElementById('filtro_dirigido')) {
                    convertirSelectABuscador('filtro_dirigido');
                }
            }
        });
    </script>


    <style>
        /* 1. El contenedor padre debe ser relativo */
        .custom-hover-wrapper {
            position: relative;
            display: inline-block;
        }

        /* 2. La tarjeta flotante oculta por defecto */
        .custom-hover-card {
            visibility: hidden;
            opacity: 0;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1050;
            min-width: 220px;
            margin-top: 5px;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        /* 3. Mostrar la tarjeta al pasar el cursor sobre el wrapper */
        .custom-hover-wrapper:hover .custom-hover-card {
            visibility: visible;
            opacity: 1;
        }

        /* 4. (Truco) Un "puente" invisible para que no se cierre si mueves el mouse rápido entre el nombre y la tarjeta */
        .custom-hover-card::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 0;
            width: 100%;
            height: 10px;
            background: transparent;
        }
    </style>
@endsection