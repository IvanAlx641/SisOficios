@extends('layouts.admin')

@section('content')

<style>
    /* Estilos específicos para este formulario */
    .btn-guinda {
        background-color: #9D2449;
        color: white;
        border: none;
        padding-left: 2rem;
        padding-right: 2rem;
        font-weight: 600;
    }
    .btn-guinda:hover {
        background-color: #801d3a;
        color: white;
    }
    
    .btn-cancelar {
        color: #9D2449;
        font-weight: 600;
        text-decoration: none;
    }
    .btn-cancelar:hover {
        text-decoration: underline;
        color: #801d3a;
    }

    /* Switch Estatus */
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    .form-switch .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }
    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(157, 36, 73, 0.25);
        border-color: #9D2449;
    }

    /* ESTILO VALIDACIÓN PERSONALIZADA (Rojo con Icono) */
    .invalid-feedback {
        font-size: 0.875em;
        color: #dc3545; /* Rojo error */
        display: block;
        margin-top: 0.25rem;
    }
    
    /* Input con error: Borde rojo e icono svg de alerta */
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    /* Ajuste de tamaño de fuente inputs */
    .form-control, .form-select {
        font-size: 0.95rem;
        padding: 0.6rem 0.75rem;
    }
    
</style>

<div class="card border-0 shadow-sm rounded-3">
    
    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
        <div>
            <h4 class="fw-bold text-guinda mb-1" style="color: #9D2449;">
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
                    <label class="form-label fw-bold text-muted small">Nombre completo <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           value="{{ old('nombre', $usuario->nombre) }}" 
                           placeholder="" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted small">Correo electrónico <span class="text-danger">*</span></label>
                    <input type="email" name="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $usuario->email) }}" 
                           placeholder="" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold text-muted small mb-3">Rol de sistema <span class="text-danger">*</span></label>
                    
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($roles as $key => $label)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('rol') is-invalid @enderror" type="radio" name="rol" 
                                       id="rol_{{ $key }}" value="{{ $key }}" 
                                       {{ old('rol', $usuario->rol) == $key ? 'checked' : '' }} 
                                       onchange="toggleDependencia(this.value)" required>
                                <label class="form-check-label" for="rol_{{ $key }}">
                                    {{ $label }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    
                    @error('rol')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mt-4" id="divDependencia">
                    <label class="form-label fw-bold text-muted small">Unidad administrativa <span class="text-danger">*</span></label>
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
                <div class="col-md-6 mt-4 d-flex align-items-end">
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="inactivo" id="inputInactivo" value="{{ $usuario->inactivo }}">
                        
                        <input class="form-check-input" type="checkbox" role="switch" id="switchEstatus" 
                               {{ $usuario->inactivo ? '' : 'checked' }} onchange="toggleEstatus()">
                        
                        <label class="form-check-label ms-2 fw-bold" for="switchEstatus" id="labelEstatus">
                            {{ $usuario->inactivo ? 'Usuario inactivo' : 'Usuario activo' }}
                        </label>
                    </div>
                </div>
                @endif

            </div>

            <div class="d-flex justify-content-start align-items-center gap-3 mt-5 pt-3">
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
            divDep.style.display = 'none';
            selectDep.required = false;
            selectDep.value = ""; 
        } else {
            divDep.style.display = 'block';
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