@extends('layouts.admin')

@section('content')
<div class="card border-0 shadow-sm rounded-3">
    {{-- Header con Título y Tabs integradas --}}
    <div class="card-header bg-light border-bottom-0 pt-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-guinda mb-0">Actividades</h4>
        </div>
    </div>

    <div class="card-body p-4 bg-white">
        <ul class="nav nav-tabs border-bottom-0">
            <li class="nav-item">
                <a class="nav-link active fw-bold text-guinda border-bottom-guinda" href="#">
                    <i class="ti ti-file-description me-1"></i> Datos generales
                </a>
            </li>
            <li class="nav-item">
                @if ($actividad->exists)
                    <a class="nav-link text-muted"
                        href="{{ route('detalleactividad.index', ['actividad_id' => encrypt($actividad->id)]) }}">
                        <i class="ti ti-users me-1"></i> Detalle de las actividades
                    </a>
                @else
                    <a class="nav-link disabled text-muted" href="#" tabindex="-1" aria-disabled="true"
                        title="Guarde los datos generales primero">
                        <i class="ti ti-users me-1"></i> Detalle de las actividades
                    </a>
                @endif
            </li>
        </ul>
        <div class="bg-white p-4 mt-3 rounded-3  mb-4">
            <form action="{{ $actividad->exists ? route('actividad.update', $actividad->id) : route('actividad.store') }}" method="POST" novalidate>
                @csrf
                @if($actividad->exists)
                    @method('PUT')
                @endif

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-guinda2 small">Fecha: <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_actividad" class="form-control border-guinda @error('fecha_actividad') is-invalid @enderror" 
                            value="{{ old('fecha_actividad', optional($actividad->fecha_actividad)->format('Y-m-d') ?? date('Y-m-d')) }}" required>
                        @error('fecha_actividad')
                            <span class="invalid-feedback fw-bold">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-guinda2 small">Responsable: <span class="text-danger">*</span></label>
                        
                        {{-- 🛡️ LÓGICA DE BLOQUEO PARA EL RESPONSABLE --}}
                        @if(auth()->user()->rol === 'Responsable')
                            <input type="hidden" name="responsable_id" value="{{ auth()->id() }}">
                            <select id="select_responsable" class="form-select border-guinda bg-light" disabled>
                                <option>{{ mb_strtoupper(auth()->user()->nombre) }}</option>
                            </select>
                        @else
                            <select name="responsable_id" id="select_responsable" class="form-select border-guinda @error('responsable_id') is-invalid @enderror" required>
                                <option value="">Seleccione un responsable...</option>
                                @foreach($responsables as $id => $nombre)
                                    <option value="{{ $id }}" {{ old('responsable_id', $actividad->responsable_id) == $id ? 'selected' : '' }}>{{ mb_strtoupper($nombre) }}</option>
                                @endforeach
                            </select>
                            @error('responsable_id')
                                <span class="invalid-feedback fw-bold">{{ $message }}</span>
                            @enderror
                        @endif

                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-guinda2 small">Sistema: <span class="text-danger">*</span></label>
                        <select name="sistema_id" id="select_sistema" class="form-select border-guinda @error('sistema_id') is-invalid @enderror" required>
                            <option value="">Seleccione un sistema...</option>
                            @foreach($sistemas as $id => $sigla)
                                <option value="{{ $id }}" {{ old('sistema_id', $actividad->sistema_id) == $id ? 'selected' : '' }}>{{ mb_strtoupper($sigla) }}</option>
                            @endforeach
                        </select>
                        @error('sistema_id')
                            <span class="invalid-feedback fw-bold">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-start align-items-center gap-3 mt-4 pt-3 ">
                    <button type="submit" class="btn btn-guinda rounded-pill px-3 shadow-sm fw-bold">
                        Guardar
                    </button>
                    <a href="{{ route('actividad.index') }}" class="btn-cancelar text-guinda text-decoration-none fw-semibold">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Colores Institucionales */
    .text-guinda { color: #9D2449 !important; }
    .text-guinda2 { color: #4e1c24 !important; }
    .bg-guinda { background-color: #9D2449 !important; }
    .border-guinda { border-color: #9D2449 !important; }
    
    /* Botones */
    .btn-guinda { background-color: #9D2449; color: white; border: none; transition: 0.3s; }
    .btn-guinda:hover { background-color: #7a1c38; color: white; transform: translateY(-1px); }
    
    .btn-cancelar { transition: all 0.2s; cursor: pointer; }
    .btn-cancelar:hover { text-decoration: underline !important; opacity: 0.8; }

    /* Estilo de Pestañas (Tabs) */
    .border-bottom-guinda { border-bottom: 3px solid #9D2449 !important; background-color: transparent !important; }
    .nav-tabs .nav-link { border: none; padding: 0.5rem 1rem; }
    .nav-tabs .nav-link.active { background-color: transparent; }

    /* ESTILOS PARA EL BUSCADOR (IMPORTANTE) */
    .searchable-dropdown-wrapper { position: relative; width: 100%; }
    .searchable-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #9D2449;
        border-radius: 8px;
        padding: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 1050;
    }
    .searchable-menu.show { display: block; }
    .searchable-option:hover { background-color: #f8f9fa; color: #9D2449; }
</style>

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
        trigger.textContent = selectedOption && selectedOption.value !== "" ? selectedOption.text : 'Seleccione una opción...';

        const menu = document.createElement('div');
        menu.className = 'searchable-menu';

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
                item.className = 'searchable-option p-2 rounded';
                item.style.cursor = 'pointer';
                item.textContent = option.text;

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
        // Solo convertimos el responsable a buscador SI NO es el rol Responsable
        @if(auth()->user()->rol !== 'Responsable')
            convertirSelectABuscador('select_responsable');
        @endif
        
        convertirSelectABuscador('select_sistema');
    });
</script>
@endsection