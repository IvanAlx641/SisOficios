@extends('layouts.admin')

@section('content')

<div class="card border- shadow-sm rounded-3">
    
    <div class="card-header bg-light border-bottom-0 pt-4 px-4 ">
        <div>
            <h4 class="fw-bold text-guinda mb-1">
                {{ $usuario->exists ? 'Editar usuario' : 'Gestión de usuarios' }}
            </h4>
        </div>
    </div>

    <div class="card-body p-4">
        
        @if ($usuario->exists)
            <form action="{{ route('usuario.update', $usuario->id) }}" method="POST" novalidate>
            @method('PUT')
        @else
            <form action="{{ route('usuario.store') }}" method="POST" novalidate>
        @endif

            @csrf

            <div class="row g-4">
                
                <div class="col-md-6">
                    <label class="form-label fw-bold text-guinda2">Nombre: <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" 
                        class="form-control @error('nombre') is-invalid @enderror" 
                        value="{{ old('nombre', $usuario->nombre) }}" 
                        placeholder="Ej. Juan Pérez García" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-guinda2">Correo electrónico: <span class="text-danger">*</span></label>
                    <input type="email" name="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        value="{{ old('email', $usuario->email) }}" 
                        placeholder="usuario@dominio.gob.mx" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold text-guinda2">Rol de sistema:<span class="text-danger">*</span></label>
                    
                    <div class="d-flex flex-wrap gap-4 p-3 border rounded-3 bg-light">
                        @foreach($roles as $key => $label)
                            @if(auth()->user()->rol === 'Titular de área' && $key === 'Administrador TI')
                                @continue
                            @endif

                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('rol') is-invalid @enderror" type="radio" name="rol" 
                                    id="rol_{{ $key }}" value="{{ $key }}" 
                                    {{ old('rol', $usuario->rol) == $key ? 'checked' : '' }} 
                                    onchange="toggleDependencia(this.value)" required>
                                <label class="form-check-label text-dark" style="font-weight: 500;" for="rol_{{ $key }}">
                                    {{ $label }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    
                    @error('rol')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mt-4" id="divDependencia">
                    <label class="form-label fw-bold text-guinda2">Unidad administrativa: <span class="text-danger">*</span></label>
                    
                    @if(auth()->user()->rol === 'Titular de área')
                        
                        <input type="hidden" name="unidad_administrativa_id" value="{{ auth()->user()->unidad_administrativa_id }}">
                        
                        <select id="selectDependencia" class="form-select bg-light text-secondary" disabled>
                            <option>{{ $unidades[auth()->user()->unidad_administrativa_id] ?? 'Mi unidad predeterminada' }}</option>
                        </select>
                        
                    @else
                        <select name="unidad_administrativa_id" id="selectDependencia" 
                                class="form-select @error('unidad_administrativa_id') is-invalid @enderror" required>
                            <option value="">Selecciona una unidad administrativa</option>
                            @foreach($unidades as $id => $nombre)
                                <option value="{{ $id }}" {{ old('unidad_administrativa_id', $usuario->unidad_administrativa_id) == $id ? 'selected' : '' }}>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('unidad_administrativa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    @endif

                </div>

                @if($usuario->exists)
                <div class="col-md-8 mt-8">
                    <div class="form-check form-switch mb-2 p-3 rounded-3">
                        <input type="hidden" name="inactivo" id="inputInactivo" value="{{ $usuario->inactivo }}">
                        
                        <div class="d-flex align-items-center ms-4">
                            <input class="form-check-input mt-0" type="checkbox" role="switch" id="switchEstatus" 
                                {{ $usuario->inactivo ? '' : 'checked' }} onchange="toggleEstatus()">
                            
                            <label class="form-check-label ms-3 fw-bold" for="switchEstatus" id="labelEstatus">
                                {{ $usuario->inactivo ? 'Usuario inactivo' : 'Usuario activo' }}
                            </label>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <div class="d-flex justify-content-start align-items-center gap-3 pt-3 ">
                <button type="submit" class="btn btn-guinda rounded-pill shadow-sm">
                    Guardar
                </button>

                <a href="{{ route('usuario.index') }}" class="btn-cancelar">
                    Cancelar
                </a>
            </div>

        </form>
    </div>
</div>

{{-- 2. SCRIPT INTEGRADO Y ADAPTADO --}}
<script>
    // --- LÓGICA DEL BUSCADOR ---
    function convertirSelectABuscador(idSelect) {
        const originalSelect = document.getElementById(idSelect);
        if (!originalSelect) return;

        const wrapperPrevio = originalSelect.parentNode.querySelector('.searchable-dropdown-wrapper');
        if (wrapperPrevio) wrapperPrevio.remove();

        const wrapper = document.createElement('div');
        wrapper.className = 'searchable-dropdown-wrapper';

        const trigger = document.createElement('button');
        trigger.className = 'form-select searchable-trigger'; 
        trigger.type = 'button';
        
        const selectedOption = originalSelect.options[originalSelect.selectedIndex];
        // Si el valor es vacío, mostramos el texto placeholder aunque tenga texto en el option
        trigger.textContent = (originalSelect.value === "") ? 'Selecciona una unidad administrativa' : selectedOption.text;

        const menu = document.createElement('div');
        menu.className = 'searchable-menu';

        const inputSearch = document.createElement('input');
        inputSearch.className = 'form-control mb-2';
        inputSearch.type = 'text';
        inputSearch.placeholder = 'Buscar...';
        inputSearch.onclick = function(e) { e.stopPropagation(); };

        const optionsList = document.createElement('div');
        optionsList.className = 'searchable-options';

        function poblarOpciones() {
            optionsList.innerHTML = '';
            Array.from(originalSelect.options).forEach(option => {
                // Omitir opciones vacías si quieres, o dejarlas
                if (option.value === "") return; 

                const item = document.createElement('div');
                item.className = 'searchable-option';
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

    // --- TU LÓGICA DE NEGOCIO (ADAPTADA) ---
    function toggleDependencia(rol) {
        const divDep = document.getElementById('divDependencia');
        const selectDep = document.getElementById('selectDependencia');

        if (rol === 'Administrador TI') {
            // Oculta el contenedor (incluyendo nuestro nuevo buscador)
            divDep.style.visibility = 'hidden'; 
            
            // Verificamos que el select no esté deshabilitado antes de modificarle atributos
            if(!selectDep.disabled) {
                selectDep.required = false;
                selectDep.value = ""; 
            }
            
            const trigger = divDep.querySelector('.searchable-trigger');
            if(trigger) trigger.textContent = 'Selecciona una unidad administrativa';
            
        } else {
            divDep.style.visibility = 'visible';
            if(!selectDep.disabled) {
                selectDep.required = true;
            }
        }
    }

    function toggleEstatus() {
        const switchElem = document.getElementById('switchEstatus');
        const inputHidden = document.getElementById('inputInactivo');
        const labelElem = document.getElementById('labelEstatus');

        if (switchElem.checked) {
            inputHidden.value = ''; 
            labelElem.innerText = 'Usuario activo';
            labelElem.classList.remove('text-danger');
            labelElem.classList.add('text-success');
        } else {
            inputHidden.value = 'X';
            labelElem.innerText = 'Usuario inactivo';
            labelElem.classList.remove('text-success');
            labelElem.classList.add('text-danger');
        }
    }
    
    // INICIALIZACIÓN
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. Convertimos el select en buscador (SOLO SI NO ES TITULAR, para no romper el select bloqueado)
        @if(auth()->user()->rol !== 'Titular de área')
            convertirSelectABuscador('selectDependencia');
        @endif

        // 2. Aplicamos la lógica del rol (esto ocultará el buscador si es Admin TI)
        const rolSeleccionado = document.querySelector('input[name="rol"]:checked');
        if (rolSeleccionado) {
            toggleDependencia(rolSeleccionado.value);
        }

        // 3. Estatus
        if(document.getElementById('switchEstatus')) {
            toggleEstatus();
        }
    });
</script>

@endsection