@extends('layouts.admin')

@section('styleFile')
<style>
    /* Colores institucionales y generales */
    .bg-guinda { background-color: #9D2449 !important; }
    .text-guinda { color: #9D2449 !important; }
    .text-guinda2 { color: #6e1732 !important; } /* Un tono un poco más oscuro para textos */
    .border-guinda { border: 1px solid #9D2449 !important; }
    .btn-guinda { background-color: #9D2449; color: white; border: none; }
    .btn-guinda:hover { background-color: #7a1c38; color: white; }
    .btn-outline-guinda { color: #9D2449; border-color: #9D2449; }
    .btn-outline-guinda:hover { background-color: #9D2449; color: white; }

    /* CSS del Buscador (Igual a tu imagen) */
    .searchable-dropdown-wrapper { position: relative; width: 100%; }
    
    .searchable-menu { 
        display: none; position: absolute; top: 100%; left: 0; width: 100%; 
        background: #fff; border: 1px solid #ced4da; border-radius: 0.5rem; 
        box-shadow: 0 4px 10px rgba(0,0,0,.08); padding: 0.75rem; z-index: 1050; 
        margin-top: 4px;
    }
    .searchable-menu.show { display: block; }
    
    /* El input de buscar como en tu imagen */
    .custom-search-input {
        border-radius: 0.6rem !important; /* Bordes redondeados */
        border: 2px solid #e0b6c3 !important; /* Un guinda suavecito inicial */
        padding: 0.6rem 1rem;
        color: #6c757d;
        transition: all 0.2s;
    }
    .custom-search-input:focus {
        border-color: #9D2449 !important; /* Guinda fuerte al dar clic */
        box-shadow: 0 0 0 0.2rem rgba(157, 36, 73, 0.15) !important;
        outline: none;
    }

    .searchable-trigger::after {
        display: inline-block; margin-left: .255em; vertical-align: .255em;
        content: ""; border-top: .3em solid; border-right: .3em solid transparent;
        border-bottom: 0; border-left: .3em solid transparent; float: right; margin-top: 8px;
    }

    /* Contenedor de la lista con Scrollbar */
    .searchable-options {
        max-height: 220px;
        overflow-y: auto;
        margin-top: 0.5rem;
    }
    .searchable-option {
        border-radius: 0.25rem;
        color: #333;
        font-size: 0.95rem;
        cursor: pointer;
    }
    
    /* Scrollbar más fino para que no se vea tosco */
    .searchable-options::-webkit-scrollbar { width: 6px; }
    .searchable-options::-webkit-scrollbar-track { background: transparent; }
    .searchable-options::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .searchable-options::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
 
@endsection

@section('content')
<div class="container-fluid">
    <div class="card w-100 position-relative bg-white border-0 shadow-sm mb-4">
        <div class="card-body pt-3 pb-2 bg-light border-bottom">
            <h4 class="fw-bold mb-0 text-guinda">Buscador de oficios</h4>
        </div>

        <div class="card-body p-4">
            <form id="formBusqueda" method="GET" action="{{ route('buscador.index') }}">
                <div class="row g-3 align-items-end">
                    
                    <div class="col-md-2">
                        <label class="form-label text-guinda small fw-bold">Número de oficio:</label>
                        <input type="text" name="numero_oficio" class="form-control border-guinda" value="{{ request('numero_oficio') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label text-guinda small fw-bold">Fecha de recepción del:</label>
                        <input type="date" name="fecha_del" class="form-control border-guinda" value="{{ request('fecha_del') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label text-guinda small fw-bold">al:</label>
                        <input type="date" name="fecha_al" class="form-control border-guinda" value="{{ request('fecha_al') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text-guinda small fw-bold">Dirigido a:</label>
                        <select name="dirigido_id" id="filtro_dirigido" class="form-select border-guinda">
                            <option value="Todos">Todos</option>
                            @foreach($usuarios as $id => $nombre)
                                <option value="{{ $id }}" {{ request('dirigido_id') == $id ? 'selected' : '' }}>{{ mb_strtoupper($nombre) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text-guinda small fw-bold">Solicitado por:</label>
                        <select name="solicitado_por_id" id="filtro_solicitado" class="form-select border-guinda">
                            <option value="Todos">Todos</option>
                            @foreach($usuarios as $id => $nombre)
                                <option value="{{ $id }}" {{ request('solicitado_por_id') == $id ? 'selected' : '' }}>{{ mb_strtoupper($nombre) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mt-3">
                        <label class="form-label text-guinda small fw-bold">Estatus:</label>
                        <select name="estatus" id="filtro_estatus" class="form-select border-guinda">
                            @foreach(['Todos', 'Pendientes', 'Turnados', 'Concluidos', 'Atendidos', 'Cancelados', 'Eliminados'] as $est)
                                <option value="{{ $est }}" {{ request('estatus') == $est ? 'selected' : '' }}>{{ $est }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mt-3">
                        <label class="form-label text-guinda small fw-bold">Sistema:</label>
                        <select name="sistema_id" id="filtro_sistema" class="form-select border-guinda">
                            <option value="Todos">Todos</option>
                            @foreach($sistemas as $id => $nombre)
                                <option value="{{ $id }}" {{ request('sistema_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mt-3">
                        <label class="form-label text-guinda small fw-bold">Tipo de requerimiento:</label>
                        <select name="tipo_requerimiento_id" id="filtro_requerimiento" class="form-select border-guinda">
                            <option value="Todos">Todos</option>
                            <option value="1" {{ request('tipo_requerimiento_id') == '1' ? 'selected' : '' }}>Cambios menores en el funcionamiento</option>
                            <option value="2" {{ request('tipo_requerimiento_id') == '2' ? 'selected' : '' }}>Reingeniería de sistemas</option>
                            <option value="3" {{ request('tipo_requerimiento_id') == '3' ? 'selected' : '' }}>Cambios a datos</option>
                        </select>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label class="form-label text-guinda small fw-bold">Descripción breve:</label>
                        <input type="text" name="descripcion" class="form-control border-guinda" value="{{ request('descripcion') }}">
                    </div>

                    <div class="col-md-1 mt-3 text-end">
                        <button type="submit" class="btn btn-guinda w-100 rounded">Buscar</button>
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
                            <th class="ps-4 py-3 text-white">Número de oficio</th>
                            <th class="py-3 text-center text-white">Fecha de<br>recepción</th>
                            <th class="py-3 text-white">Dirigido a</th>
                            <th class="py-3 text-white">Solicitado por</th>
                            <th class="py-3 text-center text-white">Fecha de<br>turno</th>
                            <th class="py-3 text-white">Sistema</th>
                            <th class="py-3 text-white">Tipo de requerimiento</th>
                            <th class="py-3 text-center text-white">Ver<br>oficio</th>
                            <th class="py-3 text-center text-white">Oficio de<br>respuesta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($oficios as $oficio)
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('buscador.show', $oficio->id) }}" class="text-guinda fw-bold d-block text-decoration-none">
                                        {{ $oficio->numero_oficio }}
                                    </a>
                                    
                                    @php
                                        $badgeClass = match($oficio->estatus ?? 'Atendido') {
                                            'Pendiente' => 'bg-warning text-white',
                                            'Turnado' => 'bg-info text-white',
                                            'Concluido', 'Atendido' => 'bg-success text-white',
                                            'Cancelado' => 'bg-danger text-white',
                                            default => 'bg-secondary text-white',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} rounded-pill mt-1">{{ $oficio->estatus ?? 'Atendido' }}</span>
                                </td>
                                
                                <td class="text-center text-muted small">{{ $oficio->fecha_recepcion ? \Carbon\Carbon::parse($oficio->fecha_recepcion)->format('d/m/Y') : '-' }}</td>
                                <td class="small text-muted">{{ mb_strtoupper($oficio->areaDirigido->nombre_unidad_administrativa ?? 'N/A') }}</td>
                                <td class="small text-muted">
                                    @foreach($oficio->solicitantes as $solicitante)
                                        {{ mb_strtoupper($solicitante->nombre) }}<br>
                                    @endforeach
                                </td>
                                <td class="text-center text-muted small">{{ $oficio->fecha_turno ? \Carbon\Carbon::parse($oficio->fecha_turno)->format('d/m/Y') : '-' }}</td>
                                <td class="small text-muted">{{ $oficio->sistema->nombre_sistema ?? 'N/A' }}</td>
                                <td class="small text-muted">{{ $oficio->tipoRequerimiento->tipo_requerimiento ?? 'N/A' }}</td>
                                
                                <td class="text-center">
                                    @if($oficio->url_oficio)
                                        <a href="{{ asset($oficio->url_oficio) }}" class="text-guinda fs-4" title="Ver documento oficial" target="_blank">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    @else
                                        <span class="text-muted"><i class="ti ti-eye-off"></i></span>
                                    @endif
                                </td>
                                
                                <td class="text-center">
                                    @if($oficio->respuestasOficios && $oficio->respuestasOficios->count() > 0)
                                        @php
                                            $popoverHtml = "<ul class='list-unstyled mb-0 text-start'>";
                                            foreach($oficio->respuestasOficios as $resp) {
                                                $fecha = $resp->fecha_respuesta ? \Carbon\Carbon::parse($resp->fecha_respuesta)->format('d/m/Y') : 'S/F';
                                                $url = $resp->url_oficio_respuesta ?? '#';
                                                $popoverHtml .= "<li class='mb-2 pb-1 border-bottom'><a href='{$url}' target='_blank' class='text-guinda fw-bold text-decoration-none'><i class='ti ti-file-text me-1'></i>{$resp->numero_oficio_respuesta}</a><br><small class='text-muted'>({$fecha})</small></li>";
                                            }
                                            $popoverHtml .= "</ul>";
                                        @endphp
                                        
                                        <button type="button" class="btn border-0 text-guinda p-0" 
                                            data-bs-toggle="popover" 
                                            data-bs-trigger="click" 
                                            title="Oficios de respuesta" 
                                            data-bs-html="true" 
                                            data-bs-content="{{ $popoverHtml }}">
                                            <i class="ti ti-file-text fs-4"></i> <i class="ti ti-arrow-down small"></i>
                                        </button>
                                    @else
                                        <span class="text-muted"><i class="ti ti-minus"></i></span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">No se han encontrado oficios con esos criterios.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">{{ $oficios->links() }}</div>
        </div>
    </div>
</div>
@endsection

@section('scriptFile')
<script>
    function convertirSelectABuscador(idSelect) {
        const originalSelect = document.getElementById(idSelect);
        if (!originalSelect) return;

        // Limpiar si ya existe el contenedor
        const wrapperPrevio = originalSelect.parentNode.querySelector('.searchable-dropdown-wrapper');
        if (wrapperPrevio) wrapperPrevio.remove();

        // Contenedor principal
        const wrapper = document.createElement('div');
        wrapper.className = 'searchable-dropdown-wrapper';

        // Botón que simula el select
        const trigger = document.createElement('button');
        trigger.className = 'form-select searchable-trigger border-guinda text-start text-truncate bg-white'; 
        trigger.type = 'button';
        
        const selectedOption = originalSelect.options[originalSelect.selectedIndex];
        trigger.textContent = selectedOption ? selectedOption.text : 'Todas las unidades';

        // Menú desplegable
        const menu = document.createElement('div');
        menu.className = 'searchable-menu';

        // Cuadro de búsqueda (Estilizado como tu imagen)
        const inputSearch = document.createElement('input');
        inputSearch.className = 'form-control mb-2 border-guinda custom-search-input';
        inputSearch.type = 'text';
        inputSearch.placeholder = 'Buscar...';
        inputSearch.autocomplete = 'off';
        
        // Evitar que al dar 'Enter' se recargue la página o se mande el formulario antes de tiempo
        inputSearch.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') e.preventDefault();
        });

        // Contenedor de las opciones
        const optionsList = document.createElement('div');
        optionsList.className = 'searchable-options';

        function poblarOpciones() {
            optionsList.innerHTML = '';
            Array.from(originalSelect.options).forEach(option => {
                const item = document.createElement('div');
                item.className = 'searchable-option p-2';
                item.textContent = option.text;
                
                // Efecto hover
                item.addEventListener('mouseover', () => { item.style.backgroundColor = '#f8f9fa'; });
                item.addEventListener('mouseout', () => { item.style.backgroundColor = 'transparent'; });

                // Al dar clic en una opción
                item.addEventListener('click', () => {
                    originalSelect.value = option.value;
                    trigger.textContent = option.text;
                    menu.classList.remove('show');
                    inputSearch.value = '';
                    filtrarOpciones('');
                    // Esto dispara el "onchange=this.form.submit()" de tu select original
                    originalSelect.dispatchEvent(new Event('change')); 
                });
                optionsList.appendChild(item);
            });
        }
        poblarOpciones();

        // Función para ignorar acentos en la búsqueda
        function normalizarTexto(texto) {
            return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
        }

        // Filtrado en tiempo real
        function filtrarOpciones(texto) {
            const items = optionsList.querySelectorAll('.searchable-option');
            const filtro = normalizarTexto(texto);
            items.forEach(item => {
                const coincide = normalizarTexto(item.textContent).includes(filtro);
                item.style.display = coincide ? 'block' : 'none';
            });
        }

        inputSearch.addEventListener('input', (e) => filtrarOpciones(e.target.value));

        // Abrir / Cerrar el menú principal
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            // Cierra otros menús abiertos
            document.querySelectorAll('.searchable-menu').forEach(m => { 
                if(m !== menu) m.classList.remove('show'); 
            });
            menu.classList.toggle('show');
            if(menu.classList.contains('show')) {
                setTimeout(() => inputSearch.focus(), 50); // Dar foco al input
            }
            e.stopPropagation();
        });

        // ESTA ES LA MAGIA: Evita que el menú se cierre al dar clic en la barra de búsqueda
        menu.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Cerrar el menú si das clic fuera de él
        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) {
                menu.classList.remove('show');
            }
        });

        menu.appendChild(inputSearch);
        menu.appendChild(optionsList);
        wrapper.appendChild(trigger);
        wrapper.appendChild(menu);

        originalSelect.parentNode.insertBefore(wrapper, originalSelect.nextSibling);
        originalSelect.style.display = 'none'; // Oculta el select feo original
    }

    document.addEventListener("DOMContentLoaded", function() {
        convertirSelectABuscador('filtro_dirigido');
    });
</script>
@endsection