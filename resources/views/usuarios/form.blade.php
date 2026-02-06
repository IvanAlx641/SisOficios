@extends('layouts.admin')

@section('content')

<div class="card border-0 shadow-sm rounded-3">
    
    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
        <div>
            <h4 class="fw-bold text-guinda mb-1">
                {{ $usuario->exists ? 'Editar usuario' : 'Registrar nuevo usuario' }}
            </h4>
            <p class="text-muted small mb-0">
                Complete la información requerida. Los campos con <span class="text-danger">*</span> son obligatorios.
            </p>
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
                    <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           value="{{ old('nombre', $usuario->nombre) }}" 
                           placeholder="Ej. Juan Pérez García" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                    <input type="email" name="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        value="{{ old('email', $usuario->email) }}" 
                        placeholder="usuario@dominio.gob.mx" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label mb-3">Rol de sistema <span class="text-danger">*</span></label>
                    
                    <div class="d-flex flex-wrap gap-4 p-3 border rounded-3 bg-light">
                        @foreach($roles as $key => $label)
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
                    <label class="form-label">Unidad administrativa <span class="text-danger">*</span></label>
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

            <div class="d-flex justify-content-start align-items-center gap-3 mt-5 pt-3 border-top">
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

<script>
    function toggleDependencia(rol) {
        const divDep = document.getElementById('divDependencia');
        const selectDep = document.getElementById('selectDependencia');

        if (rol === 'Administrador TI') {
            // USAMOS VISIBILITY HIDDEN EN LUGAR DE DISPLAY NONE
            // Esto oculta la Unidad Administrativa pero mantiene el "hueco"
            // garantizando que el Switch DEBAJO no se mueva hacia arriba.
            divDep.style.visibility = 'hidden'; 
            
            selectDep.required = false;
            selectDep.value = ""; 
        } else {
            divDep.style.visibility = 'visible';
            selectDep.required = true;
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
    
    document.addEventListener("DOMContentLoaded", function() {
        const rolSeleccionado = document.querySelector('input[name="rol"]:checked');
        if (rolSeleccionado) {
            toggleDependencia(rolSeleccionado.value);
        }
        if(document.getElementById('switchEstatus')) {
            toggleEstatus();
        }
    });
</script>

@endsection