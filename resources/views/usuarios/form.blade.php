@extends('layouts.admin')

@section('content')

<style>
    /* --- VARIABLES DE COLOR (Igual que en el Index) --- */
    :root {
        --gold-color: #C09F62;
        --gold-hover: #A88A52;
        --guinda-color: #9D2449;
        --guinda-hover: #801d3a;
    }

    /* --- ESTILOS DE FORMULARIO --- */
    
    /* Labels en Guinda */
    .form-label {
        color: var(--guinda-color);
        font-weight: 700 !important;
        font-size: 0.85rem;
    }

    /* Botón Guinda Sólido */
    .btn-guinda {
        background-color: var(--guinda-color);
        color: white;
        border: none;
        padding-left: 2rem;
        padding-right: 2rem;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-guinda:hover {
        background-color: var(--guinda-hover);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    /* Enlace Cancelar */
    .btn-cancelar {
        color: var(--guinda-color);
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s;
    }
    .btn-cancelar:hover {
        text-decoration: underline;
        color: var(--guinda-hover);
    }

    /* Switch Estatus Personalizado */
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        background-color: #e9ecef;
        border-color: #dee2e6;
        cursor: pointer;
    }
    .form-switch .form-check-input:checked {
        background-color: #28a745; /* Verde Activo */
        border-color: #28a745;
    }
    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(157, 36, 73, 0.25); /* Sombra Guinda */
        border-color: var(--guinda-color);
    }

    /* Inputs y Selects */
    .form-control, .form-select {
        font-size: 0.95rem;
        padding: 0.6rem 0.75rem;
        border-color: #dee2e6;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--guinda-color);
        box-shadow: 0 0 0 0.25rem rgba(157, 36, 73, 0.25);
    }

    /* --- VALIDACIÓN (Rojo) --- */
    .invalid-feedback {
        font-size: 0.8em;
        color: #dc3545;
        display: block;
        margin-top: 0.25rem;
        font-weight: 600;
    }
    
    /* Icono de error en input */
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
</style>

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
                    <i class="ti ti-device-floppy me-2"></i> Guardar
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