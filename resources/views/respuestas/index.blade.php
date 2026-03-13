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
                <h4 class="fw-bold mb-0 text-guinda">Respuestas</h4>
            </div>

            <div class="card-body p-3 p-md-4">
                <form action="{{ route('respuestas.index') }}" method="GET">
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

                                        <input type="radio" class="btn-check" name="estatus" value="Atendido"
                                            id="st_atendido" onchange="this.form.submit()"
                                            {{ $request->estatus == 'Atendido' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary btn-sm px-3 py-2 badge-filtro-btn text-nowrap"
                                            for="st_atendido">Atendidos</label>

                                        <input type="radio" class="btn-check" name="estatus" value="Concluido"
                                            id="st_concluido" onchange="this.form.submit()"
                                            {{ $request->estatus == 'Conluido' ? 'checked' : '' }}>
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
                    <table class="table table-hover align-middle mb-0" style="min-width: 900px;">
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
                                <th class="py-3 text-left text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Sistema</h6>
                                </th>
                                <th class="text-center py-3 text-nowrap">
                                    <h6 class="text-white form-label fw-bold small mb-0">Ver<br>oficio</h6>
                                </th>
                                <th class="text-center py-3 text-nowrap">
                                    <h6 class="text-white form-label fw-bold small mb-0">Oficios de<br>Respuesta</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($oficios as $oficio)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex flex-column">
                                            @if ($oficio->solicitud_conjunta === 'X')
                                                <a href="{{ route('detallerespuestas.index', $oficio->id) }}"
                                                    class="fw-bold mb-1 fs-3 link-oficio-gris mb-1">
                                                    {{ $oficio->numero_oficio }}
                                                </a>
                                            @else
                                                <a href="javascript:void(0)" class="fw-bold mb-1 fs-3 link-oficio-gris mb-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalRespuesta{{ $oficio->id }}">
                                                    {{ $oficio->numero_oficio }}
                                                </a>
                                            @endif
                                            @php
                                                $badgeClass = match ($oficio->estatus) {
                                                    'Turnado' => 'bg-info text-white',
                                                    'Concluido' => 'bg-success text-white',
                                                    'Atendido' => 'bg-primary text-white',
                                                    default => 'bg-secondary text-white',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }} rounded-pill "
                                                style="width: fit-content; font-size: 0.75rem;">{{ $oficio->estatus }}</span>
                                        </div>
                                    </td>

                                    <td class="text-center text-wrap small">
                                        {{ $oficio->fecha_recepcion ? $oficio->fecha_recepcion->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="small text-wrap text-left  text-uppercase">
                                        {{ $oficio->areaDirigido->nombre_unidad_administrativa ?? '-' }}</td>

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
                                                        Solicitantes</div>
                                                    <div class="px-3 py-2 text-wrap small">
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

                                    <td class="text-left  text-wrap small">
                                        {{ $oficio->sistema->sigla_sistema ?? 'N/A' }}</td>

                                    <td class="text-center">
                                        <a href="{{ $oficio->url_oficio ?? '#' }}" target="_blank"
                                            class="text-guinda fs-4" title="Ver documento PDF">
                                            <i class="ti ti-eye"></i>
                                        </a>
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
                                            <span class="text-wrap"><i class="ti ti-minus"></i></span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="ti ti-file-off fs-1 text-muted d-block mb-2"></i>
                                        <span class="text-muted">No se encontraron oficios.</span>
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
        @php
            // LÓGICA: Obtenemos la respuesta si ya existe
            $respuesta = $oficio->respuestasOficios->first();
        @endphp

        <div class="modal fade" id="modalRespuesta{{ $oficio->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold text-guinda">Respuesta </h5>
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

                        {{-- LÓGICA: Cambia la ruta si existe la respuesta --}}
                        <form
                            action="{{ $respuesta ? route('respuestas.update', $respuesta->id) : route('respuestas.store', $oficio->id) }}"
                            method="POST" novalidate>
                            @csrf

                            {{-- LÓGICA: Método PUT para editar --}}
                            @if ($respuesta)
                                @method('PUT')
                            @endif

                            <input type="hidden" name="oficio_id" value="{{ $oficio->id }}">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-guinda2 small">Fecha de la respuesta:<span
                                            class="text-danger">*</span></label>
                                    {{-- LÓGICA: Value actualizado --}}
                                    <input type="date" name="fecha_respuesta"
                                        value="{{ old('fecha_respuesta', $respuesta ? \Carbon\Carbon::parse($respuesta->fecha_respuesta)->format('Y-m-d') : date('Y-m-d')) }}"
                                        class="form-control border-guinda @error('fecha_respuesta') is-invalid @enderror">
                                    @error('fecha_respuesta')
                                        <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label fw-bold text-guinda2 small">Núm. de oficio de
                                        respuesta:<span class="text-danger">*</span></label>
                                    {{-- LÓGICA: Value actualizado --}}
                                    <input type="text" name="numero_oficio_respuesta"
                                        value="{{ old('numero_oficio_respuesta', $respuesta->numero_oficio_respuesta ?? '') }}"
                                        class="form-control border-guinda @error('numero_oficio_respuesta') is-invalid @enderror"
                                        placeholder="Ej. 21808000020000L-001/2026">
                                    @error('numero_oficio_respuesta')
                                        <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                    @enderror
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
                                    <label class="form-label fw-bold text-guinda2 small">URL del documento de respuesta
                                        (Opcional)
                                        :</label>
                                    {{-- LÓGICA: Value actualizado --}}
                                    <input type="url" name="url_oficio_respuesta"
                                        value="{{ old('url_oficio_respuesta', $respuesta->url_oficio_respuesta ?? '') }}"
                                        class="form-control border-guinda @error('url_oficio_respuesta') is-invalid @enderror"
                                        placeholder="https://...">
                                    @error('url_oficio_respuesta')
                                        <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-2">
                                    <label class="form-label fw-bold text-guinda2 small">Descripción de la
                                        respuesta:<span class="text-danger">*</span></label>
                                    {{-- LÓGICA: Textarea interior actualizado --}}
                                    <textarea name="descripción_respuesta_oficio" rows="5"
                                        placeholder="Escriba la descripción de la respuesta aquí..."
                                        class="form-control border-guinda @error('descripción_respuesta_oficio') is-invalid @enderror">{{ old('descripción_respuesta_oficio', $respuesta->descripción_respuesta_oficio ?? '') }}</textarea>
                                    @error('descripción_respuesta_oficio')
                                        <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex align-items-center pt-2 mt-1">
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

        @if ($errors->any() && old('oficio_id') == $oficio->id)
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    setTimeout(function() {
                        if (typeof jQuery !== 'undefined') {
                            $('#modalRespuesta{{ $oficio->id }}').modal('show');
                        } else {
                            var myModal = new bootstrap.Modal(document.getElementById(
                                'modalRespuesta{{ $oficio->id }}'));
                            myModal.show();
                        }
                    }, 300);
                });
            </script>
        @endif
    @endforeach

    <style>
        .border-guinda {
            border-color: #9D2449 !important;
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

        .hover-guinda:hover {
            color: #9D2449 !important;
            text-decoration: underline !important;
        }

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
            @foreach ($oficios as $oficio)
                if (typeof convertirSelectABuscador === 'function') {
                    convertirSelectABuscador('dirigido_a_{{ $oficio->id }}');
                    convertirSelectABuscador('firmado_por_{{ $oficio->id }}');
                }
            @endforeach
        });
        // --- NUEVO CÓDIGO: Limpiar validaciones al cerrar cualquier modal ---
        var modals = document.querySelectorAll('.modal');
        modals.forEach(function(modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                var form = this.querySelector('form');
                if (form) {
                    // 1. Quitar los bordes rojos de los inputs, selects y textareas
                    var invalidInputs = form.querySelectorAll('.is-invalid');
                    invalidInputs.forEach(function(input) {
                        input.classList.remove('is-invalid');
                    });

                    // 2. Ocultar los textos de error generados por Laravel
                    var errorFeedbacks = form.querySelectorAll('.invalid-feedback');
                    errorFeedbacks.forEach(function(feedback) {
                        feedback.style.display = 'none';
                    });

                    // 3. Resetear el formulario para limpiar los datos mal ingresados
                    form.reset();
                }
            });
        });
        // ----------
    </script>
@endsection
