@extends('layouts.admin')

@section('content')

    <style>
        /* Ajustes responsivos exclusivos para celulares (no afectan la vista en PC) */
        @media (max-width: 767.98px) {
            .badge-filtro-btn {
                font-size: 0.7rem !important;
                padding: 0.3rem 0.5rem !important;
            }

            .btn-nuevo-responsive {
                font-size: 0.85rem !important;
                padding-top: 0.4rem !important;
                padding-bottom: 0.4rem !important;
                width: auto !important;
                /* Evita que ocupe todo el espacio en pantallas pequeñas */
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }
        }
    </style>

    <div class="container-fluid px-2 px-md-3">

        <div class="card w-100 position-relative border-0 shadow-sm mb-3">
            <div class="card-body pt-2 py-3 bg-light">
                <div class="row align-items-center">
                    <div class="col-7 col-md-9">
                        <h4 class="fw-bold mb-0 text-guinda">Registro</h4>
                    </div>
                    <div class="col-5 col-md-3 text-end">
                        <a href="{{ route('oficio.create') }}"
                            class="btn btn-guinda w-75 py-2 shadow-sm rounded-pill btn-nuevo-responsive">
                            Nuevo
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-3 p-md-4">
                <form action="{{ route('oficio.index') }}" method="GET" id="filterForm">
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
                                    <label class="btn btn-outline-warning btn-sm px-3 py-2 badge-filtro-btn text-nowrap"
                                        for="st_pendiente">Pendientes</label>

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
                    <table class="table table-hover mb-0 align-middle" style="min-width: 900px;">
                        <thead class="bg-guinda text-white">
                            <tr>
                                <th class="ps-4 py-3">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Número de oficio</h6>
                                </th>
                                <th class="py-3">
                                    <h6 class="text-white text-center form-label fw-bold small mb-0">Fecha de recepción</h6>
                                </th>
                                <th class="py-3">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Dirigido a</h6>
                                </th>
                                <th class="py-3">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Solicitado por</h6>
                                </th>
                                <th class="py-3">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Asignado a</h6>
                                </th>
                                <th class="text-center py-3">
                                    <h6 class="text-white text-center form-label fw-bold small mb-0">Notificar</h6>
                                </th>
                                <th class="text-center py-3">
                                    <h6 class="text-white text-center form-label fw-bold small mb-0">Ver oficio</h6>
                                </th>
                                <th class="text-center py-3">
                                    <h6 class="text-white text-center form-label fw-bold small mb-0">Eliminar</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($oficios as $oficio)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex flex-column">

                                            <a href="{{ route('oficio.edit', $oficio->id) }}"
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
                                        <div class="text-center text-wrap small">
                                            {{ $oficio->fecha_recepcion->format('d/m/Y') }}</div>
                                    </td>

                                    <td>
                                        <div class="small text-wrap text-left"
                                            title="{{ $oficio->areaDirigido->nombre_unidad_administrativa ?? 'N/A' }}">
                                            {{ $oficio->areaDirigido->nombre_unidad_administrativa ?? 'N/A' }}
                                        </div>
                                    </td>

                                    <td class="small text-wrap">
                                        @if (isset($oficio->solicitantes) && $oficio->solicitantes->count() > 1)
                                            <div class="custom-hover-wrapper position-relative d-inline-block">
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
                                                    <div class="px-3 py-2 text-wrap small">
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
                                        <div class="small text-wrap text-left"
                                            title="{{ $oficio->areaAsignada->nombre_unidad_administrativa ?? 'N/A' }}">
                                            {{ $oficio->areaAsignada->nombre_unidad_administrativa ?? 'N/A' }}
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('oficio.notificar', $oficio->id) }}"
                                            class="btn btn-outline-secondary border-0 bg-transparent text-primary"
                                            title="Notificar registro por correo">
                                            <i class="ti ti-mail fs-5"></i>
                                        </a>
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ $oficio->url_oficio }}" target="_blank"
                                            class="btn btn-outline-guinda border-0 bg-transparent"
                                            title="Ver documento PDF">
                                            <i class="ti ti-eye fs-5"></i>
                                        </a>
                                    </td>

                                    <td class="text-center">
                                        <form action="{{ route('oficio.destroy', $oficio->id) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar definitivamente este oficio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn border-0 bg-transparent text-guinda"
                                                title="Eliminar">
                                                <i class="ti ti-trash fs-5"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="ti ti-files fs-1 text-muted mb-2 d-block"></i>
                                        <div class="text-muted">No se encontraron oficios registrados.</div>
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
        function convertirSelectABuscador(idSelect) {
            const originalSelect = document.getElementById(idSelect);
            if (!originalSelect) return;

            const wrapperPrevio = originalSelect.parentNode.querySelector('.searchable-dropdown-wrapper');
            if (wrapperPrevio) wrapperPrevio.remove();

            const wrapper = document.createElement('div');
            wrapper.className = 'searchable-dropdown-wrapper';

            const trigger = document.createElement('button');
            trigger.className = 'form-select searchable-trigger border-guinda text-start text-truncate';
            trigger.type = 'button';

            const selectedOption = originalSelect.options[originalSelect.selectedIndex];
            trigger.textContent = selectedOption ? selectedOption.text : 'Todas las unidades';

            const menu = document.createElement('div');
            menu.className = 'searchable-menu';
            menu.style.zIndex = '1050';

            const inputSearch = document.createElement('input');
            inputSearch.className = 'form-control mb-2';
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
                    const item = document.createElement('div');
                    item.className = 'searchable-option p-2';
                    item.style.cursor = 'pointer';
                    item.textContent = option.text;

                    item.addEventListener('mouseover', () => {
                        item.style.backgroundColor = '#f8f9fa';
                    });
                    item.addEventListener('mouseout', () => {
                        item.style.backgroundColor = 'transparent';
                    });

                    item.addEventListener('click', () => {
                        originalSelect.value = option.value;
                        trigger.textContent = option.text;
                        menu.classList.remove('show');
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
                document.querySelectorAll('.searchable-menu').forEach(m => {
                    if (m !== menu) m.classList.remove('show');
                });
                menu.classList.toggle('show');
                if (menu.classList.contains('show')) setTimeout(() => inputSearch.focus(), 100);
            });

            document.addEventListener('click', (e) => {
                if (!wrapper.contains(e.target)) menu.classList.remove('show');
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
        });
    </script>

    <style>
        .custom-hover-wrapper {
            position: relative;
            display: inline-block;
        }

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

        .custom-hover-wrapper:hover .custom-hover-card {
            visibility: visible;
            opacity: 1;
        }

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
