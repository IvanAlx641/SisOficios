@extends('layouts.admin')

@section('content')


<div class="container-fluid">
    
    {{-- 
       NOTA: Quité 'overflow-hidden' de esta card para que el menú desplegable 
       pueda salirse de la caja y verse completo.
    --}}
    <div class="card w-100 position-relative border-0 shadow-sm">
        <div class="card-body px-4 py-3 bg-light">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-bold mb-0 text-guinda">Solicitantes</h4>
                    <nav aria-label="breadcrumb">
                    </nav>
                </div>
                <div class="col-3 text-end">
                    <a href="{{ route('solicitante.create') }}" class="btn btn-guinda w-75 py-2 shadow-sm rounded-pill">
                        Nuevo
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('solicitante.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-guinda2 me-3 mb-0">Nombre:</label>
                        <input type="text" name="nombre" class="form-control border-guinda" 
                            placeholder="Buscar por nombre..." value="{{ $request->nombre }}">
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-bold text-guinda2 me-3 mb-0">Dependencia:</label>
                        {{-- 
                           AQUÍ ESTÁ EL CAMBIO: 
                           1. Agregué id="filtro_dependencia"
                           2. El onchange="this.form.submit()" seguirá funcionando gracias al JS
                        --}}
                        <select name="dependencia_id" id="filtro_dependencia" class="form-select border-guinda" onchange="this.form.submit()">
                            <option value="Todas">Todas las dependencias</option>
                            @foreach($dependencias as $id => $nombre)
                                <option value="{{ $id }}" {{ $request->dependencia_id == $id ? 'selected' : '' }}>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 end text-md-end">
                        <button type="submit" class="btn btn-outline-guinda w-50 fw-bold">
                            Buscar
                        </button>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-8 d-flex align-items-center flex-wrap">
                        <label class="fform-label fw-bold text-guinda2 me-3 mb-0">Estatus:</label>
                        
                        <div class="btn-group me-4 shadow-sm" role="group">
                            <input type="radio" class="btn-check" name="inactivo" value="Todas" id="st_all" 
                                   onchange="this.form.submit()" {{ ($request->inactivo == 'Todas' || !$request->filled('inactivo')) ? 'checked' : '' }}>
                            <label class="btn btn-outline-gold" for="st_all">Todos</label>

                            <input type="radio" class="btn-check" name="inactivo" value="Activas" id="st_active" 
                                   onchange="this.form.submit()" {{ $request->inactivo == 'Activas' ? 'checked' : '' }}>
                            <label class="btn btn-outline-success-custom" for="st_active">Activos</label>

                            <input type="radio" class="btn-check" name="inactivo" value="Inactivas" id="st_inactive" 
                                   onchange="this.form.submit()" {{ $request->inactivo == 'Inactivas' ? 'checked' : '' }}>
                            <label class="btn btn-outline-danger-custom" for="st_inactive">Inactivos</label>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3  ps-3 border-secondary-subtle">
                            <div class="d-flex align-items-center"><span class="status-dot dot-active"></span> <small class="text-muted fw-semibold">Activo</small></div>
                            <div class="d-flex align-items-center"><span class="status-dot dot-inactive"></span> <small class="text-muted fw-semibold">Inactivo</small></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card w-100 position-relative overflow-hidden border-0 shadow-sm mt-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-guinda text-white">
                        <tr>
                            <th class="text-center ps-4 py-3"><h6 class="text-white text-center form-label fw-bold small">Nombre</h6></th>
                            <th class="text-center py-3"><h6 class="text-white text-center form-label fw-bold small">Dependencia</h6></th>
                            <th class="text-center py-3"><h6 class="text-white text-center form-label fw-bold small">Unidad administrativa</h6></th>
                            <th class="text-center py-3"><h6 class="text-white text-center form-label fw-bold small">Cargo</h6></th>
                            <th class="text-center py-3"><h6 class="text-white text-center form-label fw-bold small">Eliminar</h6></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($solicitantes as $solicitante)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <span class="status-dot {{ $solicitante->inactivo == 'X' ? 'dot-inactive' : 'dot-active' }}" 
                                        title="{{ $solicitante->inactivo == 'X' ? 'Inactivo' : 'Activo' }}">
                                    </span>
                                    <a href="{{ route('solicitante.edit', $solicitante->id) }}" class="fw-bold mb-1 fs-3 link-oficio-gris">
                                        {{ $solicitante->nombre }}
                                    </a>
                                </div>
                            </td>
                            <td>
                                <span class="text-wrap small">
                                    {{ $solicitante->dependencia->nombre_dependencia ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-wrap small">
                                    {{ $solicitante->unidadAdministrativa->nombre_unidad_administrativa ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-wrap small">
                                    {{ $solicitante->cargo }}
                                </span>
                            </td>
                            
                            <td class="text-center">
                                <form action="{{ route('solicitante.destroy', $solicitante->id) }}" method="POST" onsubmit="return confirm('¿Eliminar registro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn border-0 bg-transparent text-guinda" title="Eliminar">
                                        <i class="ti ti-trash fs-5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No se encontraron resultados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 px-4 pb-3 d-flex justify-content-end">
                {!! $solicitantes->appends($request->all())->links() !!}
            </div>
        </div>
    </div>
</div>

{{-- 2. SCRIPT PARA ACTIVAR EL BUSCADOR --}}
<script>
    function convertirSelectABuscador(idSelect) {
        const originalSelect = document.getElementById(idSelect);
        if (!originalSelect) return;

        const wrapperPrevio = originalSelect.parentNode.querySelector('.searchable-dropdown-wrapper');
        if (wrapperPrevio) wrapperPrevio.remove();

        const wrapper = document.createElement('div');
        wrapper.className = 'searchable-dropdown-wrapper';

        const trigger = document.createElement('button');
        trigger.className = 'form-select searchable-trigger border-guinda'; // Agregué border-guinda para mantener tu estilo
        trigger.type = 'button';
        
        const selectedOption = originalSelect.options[originalSelect.selectedIndex];
        trigger.textContent = selectedOption ? selectedOption.text : 'Seleccione una opción';

        const menu = document.createElement('div');
        menu.className = 'searchable-menu';

        const inputSearch = document.createElement('input');
        inputSearch.className = 'form-control mb-2';
        inputSearch.type = 'text';
        inputSearch.placeholder = 'Buscar...';
        inputSearch.onclick = function(e) { e.stopPropagation(); }; // Evita cierre al clickear input

        const optionsList = document.createElement('div');
        optionsList.className = 'searchable-options';

        function poblarOpciones() {
            optionsList.innerHTML = '';
            Array.from(originalSelect.options).forEach(option => {
                const item = document.createElement('div');
                item.className = 'searchable-option';
                item.textContent = option.text;
                
                item.addEventListener('click', () => {
                    originalSelect.value = option.value;
                    trigger.textContent = option.text;
                    menu.classList.remove('show');
                    inputSearch.value = '';
                    filtrarOpciones('');
                    
                    // ESTO DISPARA EL SUBMIT DEL FORMULARIO
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
            document.querySelectorAll('.searchable-menu').forEach(m => { if(m !== menu) m.classList.remove('show'); });
            menu.classList.toggle('show');
            if(menu.classList.contains('show')) setTimeout(() => inputSearch.focus(), 100);
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

    // INICIALIZACIÓN
    document.addEventListener("DOMContentLoaded", function() {
        convertirSelectABuscador('filtro_dependencia');
    });
</script>

@endsection