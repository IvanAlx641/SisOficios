@extends('layouts.admin')

@section('content')

<style>
    /* Estilos Formulario */
    .btn-guinda {
        background-color: #9D2449; color: white; border: none;
        padding-left: 2rem; padding-right: 2rem; font-weight: 600;
    }
    .btn-guinda:hover { background-color: #801d3a; color: white; }
    
    .btn-cancelar { color: #9D2449; font-weight: 600; text-decoration: none; }
    .btn-cancelar:hover { text-decoration: underline; color: #801d3a; }

    /* Validación Roja */
    .invalid-feedback { font-size: 0.875em; color: #dc3545; display: block; margin-top: 0.25rem; }
    .form-control.is-invalid, .form-check-input.is-invalid {
        border-color: #dc3545;
    }
    .form-control.is-invalid {
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    
    /* Switch Estatus */
    .form-switch .form-check-input {
        width: 3em; height: 1.5em; background-color: #e9ecef; border-color: #dee2e6;
    }
    .form-switch .form-check-input:checked { background-color: #28a745; border-color: #28a745; }
    
    .form-control { font-size: 0.95rem; padding: 0.6rem 0.75rem; }
</style>

<div class="card border-0 shadow-sm rounded-3">
    
    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
        <div>
            <h4 class="fw-bold text-guinda mb-1" style="color: #9D2449;">
                {{ $tiporequerimiento->exists ? 'Editar tipo de requerimiento' : 'Registrar nuevo tipo de requerimiento' }}
            </h4>
            <p class="text-muted small mb-0">
                Complete la información requerida. Los campos con <span class="text-danger">*</span> son obligatorios.
            </p>
        </div>
    </div>

    <div class="card-body p-4">
        
        @if ($tiporequerimiento->exists)
            <form action="{{ route('tiporequerimiento.update', $tiporequerimiento->id) }}" method="POST" novalidate>
            @method('PUT')
        @else
            <form action="{{ route('tiporequerimiento.store') }}" method="POST" novalidate>
        @endif

            @csrf

            <div class="row g-4">
                
                <div class="col-md-12">
                    <label class="form-label fw-bold text-muted small">Tipo de requerimiento <span class="text-danger">*</span></label>
                    <input type="text" name="tipo_requerimiento" 
                           class="form-control @error('tipo_requerimiento') is-invalid @enderror" 
                           value="{{ old('tipo_requerimiento', $tiporequerimiento->tipo_requerimiento) }}" 
                           placeholder="" required>
                    @error('tipo_requerimiento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold text-muted small mb-3">Configuración de requerimientos <span class="text-danger">*</span></label>
                    
                    <div class="d-flex gap-5">
                        <div class="form-check">
                            <input class="form-check-input @error('requerimiento_oficio') is-invalid @enderror" 
                                   type="checkbox" name="requerimiento_oficio" id="checkOficios"
                                   {{ old('requerimiento_oficio', $tiporequerimiento->requerimiento_oficio) == 'X' ? 'checked' : '' }}>
                            <label class="form-check-label" for="checkOficios">Requerimiento de los oficios</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input @error('requerimiento_actividad') is-invalid @enderror" 
                                   type="checkbox" name="requerimiento_actividad" id="checkActividad"
                                   {{ old('requerimiento_actividad', $tiporequerimiento->requerimiento_actividad) == 'X' ? 'checked' : '' }}>
                            <label class="form-check-label" for="checkActividad">Requerimiento de las actividades</label>
                        </div>
                    </div>
                    
                    @if($errors->has('requerimiento_oficio') || $errors->has('requerimiento_actividad'))
                        <div class="invalid-feedback d-block mt-2">
                            <i class="ti ti-alert-circle me-1"></i> Debes seleccionar al menos una opción (Oficios o Actividades).
                        </div>
                    @endif
                </div>

                @if($tiporequerimiento->exists)
                <div class="col-md-12 mt-4">
                    <label class="form-label fw-bold text-muted small mb-2">Estatus del registro</label>
                    <div class="form-check form-switch">
                        <input type="hidden" name="inactivo" id="inputInactivo" value="{{ $tiporequerimiento->inactivo }}">
                        
                        <input class="form-check-input" type="checkbox" role="switch" id="switchEstatus" 
                               {{ $tiporequerimiento->inactivo == 'X' ? '' : 'checked' }} onchange="toggleEstatus()">
                        
                        <label class="form-check-label ms-2 fw-bold" for="switchEstatus" id="labelEstatus">
                            {{ $tiporequerimiento->inactivo == 'X' ? 'Registro inactivo' : 'Registro activo' }}
                        </label>
                    </div>
                </div>
                @endif

            </div>

            <div class="d-flex justify-content-start align-items-center gap-3 mt-5 pt-3 border-top">
                <button type="submit" class="btn btn-guinda rounded-pill shadow-sm">
                    Guardar
                </button>

                <a href="{{ route('tiporequerimiento.index') }}" class="btn-cancelar">
                    Cancelar
                </a>
            </div>

        </form>
    </div>
</div>

<script>
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
        if(document.getElementById('switchEstatus')) {
            toggleEstatus();
        }
    });
</script>

@endsection