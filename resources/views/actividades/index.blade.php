@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    
    <div class="card w-100 position-relative border-0 shadow-sm mb-5">
        <div class="card-body pt-3 pb-2 bg-light border-bottom d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0 text-guinda">Registro</h4>
            <a href="{{ route('actividad.create') }}" class="btn btn-guinda w-20  py-2 shadow-sm rounded-pill btn-nuevo-responsive">
                Nueva
            </a>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('actividad.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    
                    <div class="col-md-2">
                        <label class="form-label text-guinda2 small fw-bold">Fecha de la actividad del:</label>
                        <input type="date" name="fecha_inicial" class="form-control border-guinda " value="{{ request('fecha_inicial') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label text-guinda2 small fw-bold">al:</label>
                        <input type="date" name="fecha_final" class="form-control border-guinda " value="{{ request('fecha_final') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text-guinda2 small fw-bold">Responsable:</label>
                        <select name="responsable_id" id="filtro_responsable" class="form-select border-guinda text-secondary" >
                            <option value="Todos">Todos</option>
                            @foreach($responsables as $id => $nombre)
                                <option value="{{ $id }}" {{ request('responsable_id') == $id ? 'selected' : '' }}>{{ mb_strtoupper($nombre) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mt-3">
                            <label class="form-label text-guinda2 small fw-bold">Sistema:</label>
                            <select name="sistema_id" id="filtro_sistema" class="form-select border-guinda text-secondary">
                                <option value="Todos">Todos</option>
                                @foreach ($sistemas as $id => $nombre)
                                    <option value="{{ $id }}"
                                        {{ request('sistema_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                    <div class="col-md-2">
                        <div class="col-12 text-md-end mt-3 mt-md-0">
                        <button type="submit" class="btn btn-outline-guinda w-100 w-md-50 fw-bold">Buscar</button>
                    </div>
                    </div>

                    

                <div class="row mt-4">
                        <div class="col-md-12 d-flex align-items-center flex-wrap">
                            <label class="form-label fw-bold text-guinda2 me-3 mb-0">Estatus:</label>

                            <div class="btn-group shadow-sm flex-wrap" role="group">
                                <input type="radio" class="btn-check" name="estatus" value="Todas" id="estTodas"
                                    onchange="this.form.submit()"
                                    {{ $request->estatus == 'Todas' || !$request->filled('estatus') ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary btn-sm px-3 py-2" for="estTodas">Todos</label>

                                <input type="radio" class="btn-check" name="estatus" value="En proceso" id="estProceso"
                                    onchange="this.form.submit()" {{ $request->estatus == 'En proceso' ? 'checked' : '' }}>
                                <label class="btn btn-outline-warning btn-sm px-3 py-2 "
                                    for="estProceso">En proceso</label>

                                <input type="radio" class="btn-check" name="estatus" value="Atendida" id="estAtendida"
                                    onchange="this.form.submit()" {{ $request->estatus == 'Atendida' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success btn-sm px-3 py-2" for="estAtendida">Atendidas</label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse($actividades as $actividad)
            <div class="col-md-6 col-lg-6">
                <div class="card custom-card h-100 border-0 shadow-sm position-relative">
                    
                    <div class="custom-card-header text-center py-4 px-3">
                        <h4 class="text-white fw-bold mb-0 text-uppercase text-truncate" title="{{ $actividad->sistema->sigla_sistema ?? 'N/A' }}">
                            {{ $actividad->sistema->sigla_sistema ?? 'N/A' }}
                        </h4>
                       
                    </div>

                    <div class="card-body bg-white pt-4 pb-2">
                        <div class="d-flex align-items-center mb-3">
                            
                            <div>
                                <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.7rem;">Fecha</small>
                                <span class="text-black small mb-0">{{ $actividad->fecha_actividad ? $actividad->fecha_actividad->format('d/m/Y') : '-' }}</span>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            
                            <div class="w-100">
                                <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.7rem;">Responsable</small>
                                <span class="text-black small mb-0" title="{{ mb_strtoupper($actividad->responsable->nombre ?? 'N/A') }}">
                                    {{ mb_strtoupper($actividad->responsable->nombre ?? 'N/A') }}
                                </span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 border-top pt-3">
                            <span class="badge {{ $actividad->detalle_actividades_count > 0 ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded-pill px-3 py-2 fw-bold">
                                {{ $actividad->detalle_actividades_count }} Tarea(s)
                            </span>
                            
                            <div class="d-flex gap-1">
                                <a href="{{ route('actividad.edit', $actividad->id) }}" class="btn btn-sm  border rounded-circle text-guinda  title="Editar"><i class="ti ti-pencil"></i></a>
                                <a href="{{ route('detalleactividad.index', ['actividad_id' => encrypt($actividad->id)]) }}" class="btn btn-sm  border rounded-circle text-guinda " title="Añadir/Gestionar Tareas"><i class="ti ti-plus"></i></a>
                                
                                <form action="{{ route('actividad.destroy', $actividad->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de querer eliminar esta actividad?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm  border  text-guinda " {{ $actividad->detalle_actividades_count > 0 ? 'disabled' : '' }} title="Eliminar"><i class="ti ti-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>

                    @if($actividad->detalle_actividades_count > 0)
                        <div class="bg-white text-center pb-3">
                            <button class="btn btn-sm btn-more-info rounded-pill px-4 fw-bold " type="button" data-bs-toggle="collapse" data-bs-target="#collapseDetalles{{ $actividad->id }}" aria-expanded="false">
                                Ver Tareas <i class="ti ti-chevron-down ms-1 transition-icon"></i>
                            </button>
                        </div>

                        <div class="collapse" id="collapseDetalles{{ $actividad->id }}">
                            <div class="card-body bg-light border-top p-3 custom-scrollbar" style="max-height: 250px; overflow-y: auto;">
                                @foreach($actividad->detalleActividades as $detalle)
                                    <div class="border-start border-4 border-guinda bg-white p-3 mb-2 shadow-sm rounded">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge {{ $detalle->estatus == 'Atendida' ? 'bg-success' : 'bg-warning text-dark' }} px-2 py-1" style="font-size: 0.65rem;">
                                                {{ $detalle->estatus }}
                                            </span>
                                        </div>
                                        <h6 class="fw-bold text-muted text-truncate mb-1 text-small" style="font-size: 0.85rem;" title="{{ optional($detalle->tipoRequerimiento)->tipo_requerimiento ?? 'N/A' }}">
                                            {{ optional($detalle->tipoRequerimiento)->tipo_requerimiento ?? 'N/A' }}
                                        </h6>
                                        <div class="text-black small mb-0" style="font-size: 0.75rem;">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($detalle->descripcion_actividad), 80) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-white pb-4 rounded-bottom"></div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="ti ti-apps fs-1 text-muted d-block mb-3"></i>
                <span class="text-muted fs-5 fst-italic">No hay actividades registradas con esos filtros.</span>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $actividades->appends($request->all())->links() }}
    </div>
</div>

<style>
    /* Colores Institucionales */
    
    .bg-guinda { background-color: #9D2449 !important; }
    .border-guinda { border-color: #9D2449 !important; }
    
    .btn-guinda { background-color: #9D2449; color: white; border: none; transition: 0.3s; }
    .btn-guinda:hover { background-color: #7a1c38; color: white; transform: translateY(-1px); }
    .btn-outline-guinda { color: #9D2449; border-color: #9D2449; background: transparent; }
    .btn-outline-guinda:hover { background-color: #9D2449; color: white; }

    /* Estilo de la Card */
    .custom-card {
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .custom-card-header {
        background: linear-gradient(135deg, #9D2449 0%, #c4305c 100%);
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
    
    /* Iconos y Botones de la Card */
    .icon-circle {
        width: 35px; height: 35px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
    }
    .btn-more-info {
         color: #9D2449;
        border: 1px solid #9D2449; transition: all 0.3s ease;
    }
    .btn-more-info:hover {
        background-color: #9D2449; color: white;
    }
    
    /* Animación de la flechita del acordeón */
    .btn-more-info[aria-expanded="true"] .transition-icon {
        transform: rotate(180deg);
        display: inline-block;
        transition: transform 0.3s ease;
    }
    .btn-more-info[aria-expanded="false"] .transition-icon {
        transform: rotate(0deg);
        display: inline-block;
        transition: transform 0.3s ease;
    }

    /* Scrollbar personalizado para las tareas */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9D2449; }
</style>

<script>
    // JS del Buscador (El mismo que hemos usado, limpio y funcional)
    function convertirSelectABuscador(idSelect) {
        const originalSelect = document.getElementById(idSelect);
        if (!originalSelect) return;
        const wrapperPrevio = originalSelect.parentNode.querySelector('.searchable-dropdown-wrapper');
        if (wrapperPrevio) wrapperPrevio.remove();

        const wrapper = document.createElement('div');
        wrapper.className = 'searchable-dropdown-wrapper position-relative w-100';

        const trigger = document.createElement('button');
        trigger.className = 'form-select searchable-trigger border-guinda text-start text-truncate bg-white w-100 text-secondary'; 
        trigger.type = 'button';
        
        const selectedOption = originalSelect.options[originalSelect.selectedIndex];
        trigger.textContent = selectedOption && selectedOption.value !== "" ? selectedOption.text : 'Seleccione...';

        const menu = document.createElement('div');
        menu.className = 'searchable-menu bg-white border border-guinda rounded shadow-sm p-2 w-100';
        menu.style.position = 'absolute'; menu.style.top = '100%'; menu.style.left = '0';
        menu.style.zIndex = '1050'; menu.style.display = 'none'; menu.style.marginTop = '4px';

        const inputSearch = document.createElement('input');
        inputSearch.className = 'form-control mb-2 border-guinda text-secondary';
        inputSearch.type = 'text'; inputSearch.placeholder = 'Buscar...'; inputSearch.autocomplete = 'off';
        inputSearch.onclick = function(e) { e.stopPropagation(); };
        inputSearch.addEventListener('keydown', function(e) { if (e.key === 'Enter') e.preventDefault(); });

        const optionsList = document.createElement('div');
        optionsList.className = 'searchable-options';
        optionsList.style.maxHeight = '200px'; optionsList.style.overflowY = 'auto';

        function poblarOpciones() {
            optionsList.innerHTML = '';
            Array.from(originalSelect.options).forEach(option => {
                if(option.value === "") return; 
                const item = document.createElement('div');
                item.className = 'searchable-option p-2 rounded text-secondary small';
                item.style.cursor = 'pointer'; item.textContent = option.text;
                
                item.addEventListener('mouseover', () => { item.style.backgroundColor = '#F8E8EC'; item.style.color = '#9D2449'; });
                item.addEventListener('mouseout', () => { item.style.backgroundColor = 'transparent'; item.style.color = '#6c757d'; });
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    originalSelect.value = option.value;
                    trigger.textContent = option.text;
                    menu.style.display = 'none';
                    inputSearch.value = ''; filtrarOpciones('');
                    originalSelect.dispatchEvent(new Event('change')); 
                });
                optionsList.appendChild(item);
            });
        }
        poblarOpciones();

        function normalizarTexto(texto) { return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase(); }

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
            if (!isShowing) { menu.style.display = 'block'; setTimeout(() => inputSearch.focus(), 100); }
        });

        document.addEventListener('click', (e) => { if (!wrapper.contains(e.target)) menu.style.display = 'none'; });

        menu.appendChild(inputSearch); menu.appendChild(optionsList);
        wrapper.appendChild(trigger); wrapper.appendChild(menu);
        originalSelect.parentNode.insertBefore(wrapper, originalSelect.nextSibling);
        originalSelect.style.display = 'none';
    }

    document.addEventListener("DOMContentLoaded", function() {
        convertirSelectABuscador('filtro_responsable');
        convertirSelectABuscador('filtro_sistema');
    });
</script>
@endsection