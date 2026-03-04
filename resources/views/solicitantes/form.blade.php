@extends('layouts.admin')

@section('content')

<div class="card border-0 shadow-sm rounded-3">
    
    <div class="card-header bg-light border-bottom-0 pt-4 px-4">
        <div>
            <h4 class="fw-bold text-guinda mb-1">
                {{ $solicitante->exists ? 'Editar solicitante' : 'Solicitantes' }}
            </h4>
        </div>
    </div>

    <div class="card-body p-4">
        
        @if ($solicitante->exists)
            <form action="{{ route('solicitante.update', $solicitante->id) }}" method="POST" novalidate>
            @method('PUT')
        @else
            <form action="{{ route('solicitante.store') }}" method="POST" novalidate>
        @endif

            @csrf

            <div class="row g-4">
                
                <div class="col-md-12">
                    <label class="form-label">Nombre: <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                           value="{{ old('nombre', $solicitante->nombre) }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Dependencia u organismo auxiliar: <span class="text-danger">*</span></label>
                    <select name="dependencia_id" id="dependencia_id" class="form-select @error('dependencia_id') is-invalid @enderror" required>
                        <option value="">Seleccione una opción</option>
                        @foreach($dependencias as $id => $nombre)
                            <option value="{{ $id }}" {{ old('dependencia_id', $solicitante->dependencia_id) == $id ? 'selected' : '' }}>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('dependencia_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Unidad administrativa: <span class="text-danger">*</span></label>
                    <select name="unidad_administrativa_id" id="unidad_administrativa_id" class="form-select @error('unidad_administrativa_id') is-invalid @enderror" required>
                        <option value="">Seleccione primero una dependencia</option>
                        @if(isset($unidades))
                            @foreach($unidades as $id => $nombre)
                                <option value="{{ $id }}" {{ old('unidad_administrativa_id', $solicitante->unidad_administrativa_id) == $id ? 'selected' : '' }}>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('unidad_administrativa_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label">Cargo o puesto: <span class="text-danger">*</span></label>
                    <input type="text" name="cargo" class="form-control @error('cargo') is-invalid @enderror" 
                        value="{{ old('cargo', $solicitante->cargo) }}" required>
                    @error('cargo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                @if($solicitante->exists)
                    <div class="col-md-8 mt-8">
                        <div class="form-check form-switch mb-2 p-3 rounded-3">
                            <input type="hidden" name="inactivo" id="inputInactivo" value="{{ $solicitante->inactivo }}">
                            
                            <div class="d-flex align-items-center ms-4">
                                <input class="form-check-input mt-0" type="checkbox" role="switch" id="switchEstatus" 
                                    {{ $solicitante->inactivo ? '' : 'checked' }} onchange="toggleEstatus()">
                                
                                <label class="form-check-label ms-3 fw-bold" for="switchEstatus" id="labelEstatus">
                                    {{ $solicitante->inactivo ? 'Registro inactivo' : 'Registro activo' }}
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-start align-items-center gap-3  pt-3 ">
                <button type="submit" class="btn btn-guinda rounded-pill shadow-sm">
                    Guardar
                </button>
                <a href="{{ route('solicitante.index') }}" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
    // 1. AJAX para cargar unidades
    document.getElementById('dependencia_id').addEventListener('change', function() {
        var dependenciaId = this.value;
        var unidadSelect = document.getElementById('unidad_administrativa_id');
        
        unidadSelect.innerHTML = '<option value="">Cargando...</option>';
        
        if(dependenciaId) {
            fetch('/api/unidades-por-dependencia/' + dependenciaId) // Asegúrate de definir esta ruta
                .then(response => response.json())
                .then(data => {
                    unidadSelect.innerHTML = '<option value="">Seleccione una unidad</option>';
                    data.forEach(unidad => {
                        unidadSelect.innerHTML += `<option value="${unidad.id}">${unidad.nombre_unidad_administrativa}</option>`;
                    });
                });
        } else {
            unidadSelect.innerHTML = '<option value="">Seleccione primero una dependencia</option>';
        }
    });

    // 2. Switch Estatus
    function toggleEstatus() {
        const switchElem = document.getElementById('switchEstatus');
        const inputHidden = document.getElementById('inputInactivo');
        const labelElem = document.getElementById('labelEstatus');

        if (switchElem.checked) {
            inputHidden.value = ''; 
            labelElem.innerText = 'Registro activo';
            labelElem.classList.remove('text-danger');
            labelElem.classList.add('text-success');
        } else {
            inputHidden.value = 'X';
            labelElem.innerText = 'Registro inactivo';
            labelElem.classList.remove('text-success');
            labelElem.classList.add('text-danger');
        }
    }
    
    document.addEventListener("DOMContentLoaded", function() {
        if(document.getElementById('switchEstatus')) toggleEstatus();
    });
</script>

<script>
    // 1. INICIALIZAR LOS BUSCADORES AL CARGAR LA PÁGINA
    document.addEventListener("DOMContentLoaded", function() {
        // Convertimos el primer select
        convertirSelectABuscador('dependencia_id');
        
        // Convertimos el segundo select (si ya tiene datos cargados por 'old' o edición)
        convertirSelectABuscador('unidad_administrativa_id');

        // Tu código del switch Estatus
        if(document.getElementById('switchEstatus')) toggleEstatus();
    });

    // 2. TU AJAX (Ligeramente modificado)
    document.getElementById('dependencia_id').addEventListener('change', function() {
        var dependenciaId = this.value;
        var unidadSelect = document.getElementById('unidad_administrativa_id');
        
        // Limpiamos opciones mientras carga
        unidadSelect.innerHTML = '<option value="">Cargando...</option>';
        
        // Importante: Si ya habíamos convertido este select, el visual se debe actualizar
        // Así que lo regeneramos para que diga "Cargando..."
        convertirSelectABuscador('unidad_administrativa_id');
        
        if(dependenciaId) {
            fetch('/api/unidades-por-dependencia/' + dependenciaId) 
                .then(response => response.json())
                .then(data => {
                    unidadSelect.innerHTML = '<option value="">Seleccione una unidad</option>';
                    data.forEach(unidad => {
                        unidadSelect.innerHTML += `<option value="${unidad.id}">${unidad.nombre_unidad_administrativa}</option>`;
                    });

                    // >>> AQUÍ ESTÁ LA MAGIA <<<
                    // Una vez que el select oculto tiene los nuevos options,
                    // llamamos a la función de nuevo para que regenere el buscador visual
                    convertirSelectABuscador('unidad_administrativa_id'); 
                });
        } else {
            unidadSelect.innerHTML = '<option value="">Seleccione primero una dependencia</option>';
            convertirSelectABuscador('unidad_administrativa_id');
        }
    });

    // ... (Tu función toggleEstatus sigue igual) ...
</script>

@endsection