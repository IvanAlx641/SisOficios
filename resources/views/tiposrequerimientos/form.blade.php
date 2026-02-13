@extends('layouts.admin')

@section('content')


<div class="card border-0 shadow-sm rounded-3">
    
    <div class="card-header bg-light border-bottom-0 pt-4 px-4">
        <div>
            <h4 class="fw-bold text-guinda mb-1" style="color: #9D2449;">
                {{ $tiporequerimiento->exists ? 'Editar tipo de requerimiento' : 'Tipos de requerimiento' }}
            </h4>
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
                    <label class="form-label fw-bold text-muted small mb-3">Aplicacion de requerimientos <span class="text-danger">*</span></label>
                    
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