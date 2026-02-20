@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    
    <div class="card w-100 position-relative border-0 shadow-sm mb-3">
        <div class="card-body pt-2 py-3 bg-light">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-bold mb-0 text-guinda">Oficios</h4>
                </div>
                <div class="col-3 text-end">
                    <a href="{{ route('oficio.create') }}" class="btn btn-guinda w-75 py-2 shadow-sm rounded-pill">
                        Nuevo
                    </a>
                </div>
            </div>
        </div> 
        
        <div class="card-body p-4">
            <form action="{{ route('oficio.index') }}" method="GET" id="filterForm">
                <div class="row g-3 align-items-end">
                    
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-guinda2 small">Número de oficio</label>
                        <input type="text" name="numero_oficio" class="form-control border-guinda" 
                               placeholder="Buscar por número..." value="{{ $request->numero_oficio }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-guinda2 small">Dirigido a</label>
                        <select name="dirigido_id" id="filtro_dirigido" class="form-select border-guinda" onchange="this.form.submit()">
                            <option value="0">Todas las unidades</option>
                            @foreach($unidades as $id => $nombre)
                                <option value="{{ $id }}" {{ $request->dirigido_id == $id ? 'selected' : '' }}>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold text-guinda2 small">Fecha recepción</label>
                        <input type="date" name="fecha_recepcion" class="form-control border-guinda" 
                               onchange="this.form.submit()" value="{{ $request->fecha_recepcion }}">
                    </div>

                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-outline-guinda w-100 fw-bold">
                            Buscar
                        </button>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12 d-flex align-items-center flex-wrap">
                        <label class="form-label fw-bold text-guinda2 me-3 mb-0">Estatus:</label>
                        
                        <div class="btn-group shadow-sm flex-wrap" role="group">
                            <input type="radio" class="btn-check" name="estatus" value="Todos" id="st_todos" 
                                   onchange="this.form.submit()" {{ ($request->estatus == 'Todos' || !$request->filled('estatus')) ? 'checked' : '' }}>
                            <label class="btn btn-outline-secondary btn-sm px-3 py-2" for="st_todos">Todos</label>

                            <input type="radio" class="btn-check" name="estatus" value="Pendiente" id="st_pendiente" 
                                   onchange="this.form.submit()" {{ $request->estatus == 'Pendiente' ? 'checked' : '' }}>
                            <label class="btn btn-outline-warning btn-sm px-3 py-2 text-whit" for="st_pendiente">Pendientes</label>

                            <input type="radio" class="btn-check" name="estatus" value="Turnado" id="st_turnado" 
                                   onchange="this.form.submit()" {{ $request->estatus == 'Turnado' ? 'checked' : '' }}>
                            <label class="btn btn-outline-info btn-sm px-3 py-2" for="st_turnado">Turnados</label>

                            <input type="radio" class="btn-check" name="estatus" value="Concluido" id="st_concluido" 
                                   onchange="this.form.submit()" {{ $request->estatus == 'Concluido' ? 'checked' : '' }}>
                            <label class="btn btn-outline-success btn-sm px-3 py-2" for="st_concluido">Concluidos</label>

                            <input type="radio" class="btn-check" name="estatus" value="Eliminado" id="st_eliminado" 
                                   onchange="this.form.submit()" {{ $request->estatus == 'Eliminado' ? 'checked' : '' }}>
                            <label class="btn btn-outline-gold btn-sm px-3 py-2" for="st_eliminado">Eliminados</label>

                            <input type="radio" class="btn-check" name="estatus" value="Cancelado" id="st_cancelado" 
                                   onchange="this.form.submit()" {{ $request->estatus == 'Cancelado' ? 'checked' : '' }}>
                            <label class="btn btn-outline-danger btn-sm px-3 py-2" for="st_cancelado">Cancelados</label>
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
                            <th class="ps-4 py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Número de oficio</h6></th>
                            <th class="py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Fecha recepción</h6></th>
                            <th class="py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Dirigido a</h6></th>
                            <th class="py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Solicitado por</h6></th>
                            <th class="py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Asignado a</h6></th>
                            <th class="text-center py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Ver oficio</h6></th>
                            <th class="text-center py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Eliminar</h6></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($oficios as $oficio)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex flex-column">
                                    {{-- AQUÍ ESTÁ EL CAMBIO DE ESTILO DEL LINK --}}
                                    <a href="{{ route('oficio.edit', $oficio->id) }}" class="fw-bold mb-1 fs-4 link-oficio-gris">
                                        {{ $oficio->numero_oficio }}
                                    </a>

                                    @php
                                        $badgeClass = match($oficio->estatus) {
                                            'Pendiente' => 'bg-warning text-white',
                                            'Turnado' => 'bg-info text-white',
                                            'Concluido', 'Atendido' => 'bg-success text-white',
                                            'Cancelado' => 'bg-danger text-white',
                                            default => 'bg-secondary text-white',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} rounded-pill w-auto" style="width: fit-content; font-size: 0.75rem;">
                                        {{ $oficio->estatus }}
                                    </span>
                                </div>
                            </td>
                            
                            <td>
                                <div class="text-center text-muted small">{{ $oficio->fecha_recepcion->format('d/m/Y') }}</div>
                            </td>

                            <td>
                                <div class="small text-truncate" style="max-width: 200px;" title="{{ $oficio->areaDirigido->nombre_unidad_administrativa ?? 'N/A' }}">
                                    {{ $oficio->areaDirigido->nombre_unidad_administrativa ?? 'N/A' }}
                                </div>
                            </td>
                            
                            <td>
                                @if($oficio->solicitantes->count() > 0)
                                    @foreach($oficio->solicitantes as $sol)
                                        <div class="small text-muted mb-1">• {{ \Illuminate\Support\Str::limit($sol->nombre, 25) }}</div>
                                    @endforeach
                                @else
                                    <span class="text-muted small fst-italic">Sin asignar</span>
                                @endif
                            </td>

                            <td>
                                <div class="small text-truncate" style="max-width: 200px;" title="{{ $oficio->areaAsignada->nombre_unidad_administrativa ?? 'N/A' }}">
                                    {{ $oficio->areaAsignada->nombre_unidad_administrativa ?? 'N/A' }}
                                </div>
                            </td>
                            
                            <td class="text-center">
                                <a href="{{ $oficio->url_oficio }}" target="_blank" class="btn btn-outline-guinda border-0 bg-transparent" title="Ver documento PDF">
                                    <i class="ti ti-eye fs-5"></i>
                                </a>
                            </td>
                            
                            <td class="text-center">
                                <form action="{{ route('oficio.destroy', $oficio->id) }}" method="POST" onsubmit="return confirm('¿Eliminar definitivamente este oficio?');">
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
                            <td colspan="7" class="text-center py-5">
                                <i class="ti ti-files fs-1 text-muted mb-2 d-block"></i>
                                <div class="text-muted">No se encontraron oficios registrados.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 px-4 pb-3 d-flex justify-content-end">
                {!! $oficios->appends($request->all())->links() !!}
            </div>
        </div>
    </div>
</div>

<script>
    // Tu script searchable-dropdown sigue igual...
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
        inputSearch.onclick = function(e) { e.stopPropagation(); };

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
                
                item.addEventListener('mouseover', () => { item.style.backgroundColor = '#f8f9fa'; });
                item.addEventListener('mouseout', () => { item.style.backgroundColor = 'transparent'; });

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

    document.addEventListener("DOMContentLoaded", function() {
        convertirSelectABuscador('filtro_dirigido');
    });
</script>

<style>
    
</style>

@endsection