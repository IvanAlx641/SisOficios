@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card w-100 position-relative border-0 shadow-sm mb-4">
            <div class="card-body pt-3 pb-2 bg-light border-bottom">
                <h4 class="fw-bold mb-0 text-guinda">Buscador de oficios</h4>
            </div>

            <div class="card-body p-4">
                <form id="formBusqueda" method="GET" action="{{ route('buscador.index') }}">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-2">
                            <label class="form-label text-guinda2 small fw-bold">Número de oficio:</label>
                            <input type="text" name="numero_oficio" class="form-control border-guinda "
                                value="{{ request('numero_oficio') }}">
                        </div>

                        <div class="col-md-4">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fw-bold text-guinda2 small">Fecha de recepción del:</label>
                                    <input type="date" name="fecha_recepcion" class="form-control border-guinda"
                                        value="{{ $request->fecha_recepcion }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold text-guinda2 small">al:</label>
                                    <input type="date" name="fecha_recepcion_fin" class="form-control border-guinda"
                                        value="{{ $request->fecha_recepcion_fin }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label text-guinda2 small fw-bold">Dirigido a:</label>
                            <select name="dirigido_id" id="filtro_dirigido" class="form-select border-guinda text-secondary"
                                onchange="this.form.submit()">
                                <option value="Todos">Todos</option>
                                @foreach ($unidades as $id => $nombre)
                                    <option value="{{ $id }}"
                                        {{ request('dirigido_id') == $id ? 'selected' : '' }}>{{ mb_strtoupper($nombre) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label text-guinda2 small fw-bold">Solicitado por:</label>
                            <select name="solicitado_por_id" id="filtro_solicitado"
                                class="form-select border-guinda text-secondary" onchange="this.form.submit()">
                                <option value="Todos">Todos</option>
                                @foreach ($solicitantesList as $id => $nombre)
                                    <option value="{{ $id }}"
                                        {{ request('solicitado_por_id') == $id ? 'selected' : '' }}>
                                        {{ mb_strtoupper($nombre) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mt-3">
                            <label class="form-label text-guinda2 small fw-bold">Estatus:</label>
                            <select name="estatus" id="filtro_estatus" class="form-select border-guinda text-secondary"
                                onchange="this.form.submit()">
                                @foreach (['Todos', 'Pendiente', 'Turnado', 'Concluido', 'Atendido', 'Cancelado'] as $est)
                                    <option value="{{ $est }}"
                                        {{ request('estatus') == $est ? 'selected' : '' }}>{{ $est }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mt-3">
                            <label class="form-label text-guinda2 small fw-bold">Sistema:</label>
                            <select name="sistema_id" id="filtro_sistema" class="form-select border-guinda text-secondary"
                                onchange="this.form.submit()">
                                <option value="Todos">Todos</option>
                                @foreach ($sistemas as $id => $nombre)
                                    <option value="{{ $id }}"
                                        {{ request('sistema_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mt-3">
                            <label class="form-label text-guinda2 small fw-bold">Tipo de requerimiento:</label>
                            <select name="tipo_requerimiento_id" id="filtro_requerimiento"
                                class="form-select border-guinda text-secondary" onchange="this.form.submit()">
                                <option value="Todos">Todos</option>
                                @foreach ($tiposRequerimiento as $id => $nombre)
                                    <option value="{{ $id }}"
                                        {{ request('tipo_requerimiento_id') == $id ? 'selected' : '' }}>
                                        {{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label text-guinda2 small fw-bold">Descripción breve:</label>
                            <input type="text" name="descripcion" class="form-control border-guinda "
                                value="{{ request('descripcion') }}">
                        </div>

                        <div class="col-md-1 mt-3 text-end">
                            <button type="submit"
                                class="btn btn-outline-guinda w-100 fw-bold">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card w-100 position-relative border-0 shadow-sm mt-3">
            <div class="card-body p-0">
                <div class="table-responsive" style="min-height: 400px;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-guinda text-white">
                            <tr>
                                <th class="ps-4 py-3">
                                    <h6 class="text-white text-left form-label fw-bold small">Número de oficio</h6>
                                </th>
                                <th class="py-3 text-center">
                                    <h6 class="text-white text-center form-label fw-bold small">Fecha de<br>recepción</h6>
                                </th>
                                <th class="py-3">
                                    <h6 class="text-white text-left form-label fw-bold small">Dirigido a</h6>
                                </th>
                                <th class="py-3">
                                    <h6 class="text-white text-left form-label fw-bold small">Solicitado por</h6>
                                </th>
                                <th class="py-3 text-center">
                                    <h6 class="text-white text-center form-label fw-bold small">Fecha de<br>turno</h6>
                                </th>
                                <th class="py-3 text-left">
                                    <h6 class="text-white text-left form-label fw-bold small">Sistema</h6>
                                </th>
                                <th class="py-3">
                                    <h6 class="text-white text-left form-label fw-bold small">Tipo de requerimiento</h6>
                                </th>
                                <th class="py-3 text-center">
                                    <h6 class="text-white text-center form-label fw-bold small">Ver<br>oficio</h6>
                                </th>
                                <th class="py-3 text-center">
                                    <h6 class="text-white text-center form-label fw-bold small">Oficio de<br>respuesta</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($oficios as $oficio)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('buscador.show', $oficio->id) }}"
                                                class="link-hover-guinda text-muted text-left fw-bold d-block mb-1">
                                                {{ $oficio->numero_oficio }}
                                            </a>
                                            @php
                                                $badgeClass = match ($oficio->estatus ?? 'Atendido') {
                                                    'Pendiente' => 'bg-warning text-white',
                                                    'Turnado' => 'bg-info text-white',
                                                    'Concluido' => 'bg-success text-white',
                                                    'Atendido' => 'bg-primary text-white',
                                                    'Cancelado' => 'bg-danger text-white',
                                                    default => 'bg-secondary text-white',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }} rounded-pill "
                                                style="width: fit-content; font-size: 0.75rem;">{{ $oficio->estatus }}</span>
                                        </div>
                                    </td>

                                    <td class="text-center text-wrap small">
                                        {{ $oficio->fecha_recepcion ? \Carbon\Carbon::parse($oficio->fecha_recepcion)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="small text-wrap text-left text-uppercase">
                                        {{ $oficio->areaDirigido->nombre_unidad_administrativa ?? 'N/A' }}</td>

                                    <td class="small text-wrap">
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
                                                    <div class="px-3 py-2 text-wrap small">
                                                        @foreach ($oficio->solicitantes as $sol)
                                                            <div class="text-uppercase mb-1"><i
                                                                    class="ti ti-user me-1 text-guinda"></i>{{ $sol->nombre }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($oficio->solicitantes->count() == 1)
                                            <div class="text-uppercase">
                                                {{ mb_strtoupper($oficio->solicitantes->first()->nombre) }}</div>
                                        @else
                                            <span class="fst-italic text-wrap">Sin asignar</span>
                                        @endif
                                    </td>

                                    <td class="text-center text-wrap small">
                                        {{ $oficio->fecha_turno ? \Carbon\Carbon::parse($oficio->fecha_turno)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="text-left small text-wrap">
                                        {{ $oficio->sistema->sigla_sistema ?? 'N/A' }}</td>
                                    <td class="small text-left text-wrap">
                                        {{ $oficio->tipoRequerimiento->tipo_requerimiento ?? 'N/A' }}</td>

                                    <td class="text-center">
                                        @if ($oficio->url_oficio)
                                            <a href="{{ asset($oficio->url_oficio) }}" class="text-guinda fs-4"
                                                title="Ver documento oficial" target="_blank">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        @else
                                            <span class="text-wrap"><i class="ti ti-eye-off"></i></span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if ($oficio->respuestasOficios && $oficio->respuestasOficios->count() > 0)
                                            @php
                                                $popoverHtml = "<ul class='list-unstyled mb-0 text-start'>";
                                                foreach ($oficio->respuestasOficios as $resp) {
                                                    $fecha = $resp->fecha_respuesta
                                                        ? \Carbon\Carbon::parse($resp->fecha_respuesta)->format('d/m/Y')
                                                        : 'S/F';
                                                    $url = $resp->url_oficio_respuesta
                                                        ? asset($resp->url_oficio_respuesta)
                                                        : '#';
                                                    $popoverHtml .= "<li class='mb-2 pb-1 border-bottom'><a href='{$url}' target='_blank' class='text-guinda fw-bold text-decoration-none'><i class='ti ti-file-text me-1'></i>{$resp->numero_oficio_respuesta}</a><br><small class='text-wrap'>({$fecha})</small></li>";
                                                }
                                                $popoverHtml .= '</ul>';
                                            @endphp

                                            <button type="button" class="btn border-0 text-guinda p-0"
                                                data-bs-toggle="popover" data-bs-trigger="focus"
                                                title="Oficios de respuesta" data-bs-html="true"
                                                data-bs-content="{{ $popoverHtml }}">
                                                <i class="ti ti-file-text fs-4"></i> <i
                                                    class="ti ti-arrow-down small"></i>
                                            </button>
                                        @else
                                            <span class="text-muted"><i class="ti ti-minus"></i></span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">No se encontraron oficios con
                                        esos criterios de búsqueda.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-top">{{ $oficios->appends($request->all())->links() }}</div>
            </div>
        </div>
    </div>

    <style>
        /* Estilos Estándar Institucionales */

        /* Efecto Negro a Guinda Subrayado en la tabla */
        .link-hover-guinda {
            color: #9D2449 !important;
            /* Negro/Gris muy oscuro por defecto */
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .link-hover-guinda:hover {
            color: #9D2449 !important;
            /* Pasa a Guinda */
            text-decoration: underline !important;
        }

        /* CSS TARJETAS FLOTANTES (TOOLTIPS HOVER) */
        .custom-hover-wrapper .custom-hover-card {
            visibility: hidden;
            opacity: 0;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1050;
            min-width: 250px;
            transition: all 0.2s ease-in-out;
            margin-top: 10px;
        }

        .custom-hover-wrapper:hover .custom-hover-card {
            visibility: visible;
            opacity: 1;
            margin-top: 5px;
        }

        .bg-primary {
            background-color: blue !important;
            border-color: blue !important;

        }
    </style>

    <script>
        // 1. POPOVERS DE BOOTSTRAP
        document.addEventListener("DOMContentLoaded", function() {
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });

        // 2. BUSCADOR INTELIGENTE EN SELECTS (JS Seguro)
        function convertirSelectABuscador(idSelect) {
            const originalSelect = document.getElementById(idSelect);
            if (!originalSelect) return;

            const wrapperPrevio = originalSelect.parentNode.querySelector('.searchable-dropdown-wrapper');
            if (wrapperPrevio) wrapperPrevio.remove();

            const wrapper = document.createElement('div');
            wrapper.className = 'searchable-dropdown-wrapper position-relative w-100';

            const trigger = document.createElement('button');
            // Le agregamos text-secondary para que la letra base sea gris oscuro igual a todos los inputs
            trigger.className =
                'form-select searchable-trigger border-guinda text-start text-truncate bg-white w-100 text-secondary';
            trigger.type = 'button';

            const selectedOption = originalSelect.options[originalSelect.selectedIndex];
            trigger.textContent = selectedOption && selectedOption.value !== "" ? selectedOption.text : 'Seleccione...';

            const menu = document.createElement('div');
            menu.className = 'searchable-menu bg-white border border-guinda rounded shadow-sm p-2 w-100';
            menu.style.position = 'absolute';
            menu.style.top = '100%';
            menu.style.left = '0';
            menu.style.zIndex = '1050';
            menu.style.display = 'none';
            menu.style.marginTop = '4px';

            const inputSearch = document.createElement('input');
            inputSearch.className = 'form-control mb-2 border-guinda custom-search-input text-secondary';
            inputSearch.type = 'text';
            inputSearch.placeholder = 'Buscar...';
            inputSearch.autocomplete = 'off';
            inputSearch.onclick = function(e) {
                e.stopPropagation();
            };
            inputSearch.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') e.preventDefault();
            });

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
                    item.addEventListener('click', (e) => {
                        e.stopPropagation();
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

            function normalizarTexto(texto) {
                return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            }

            function filtrarOpciones(texto) {
                const items = optionsList.querySelectorAll('.searchable-option');
                const filtro = normalizarTexto(texto);
                items.forEach(item => {
                    const coincide = normalizarTexto(item.textContent).includes(filtro);
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
            // AHORA APLICAMOS EL BUSCADOR A LOS 5 SELECTORES
            convertirSelectABuscador('filtro_dirigido');
            convertirSelectABuscador('filtro_solicitado');
            convertirSelectABuscador('filtro_estatus');
            convertirSelectABuscador('filtro_sistema');
            convertirSelectABuscador('filtro_requerimiento');
        });
    </script>
@endsection
