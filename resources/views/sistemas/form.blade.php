@extends('layouts.admin')

@section('content')
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-light border-bottom-0 pt-4 px-4">
        <h4 class="fw-bold text-guinda mb-1">
            {{ $sistema->exists ? 'Editar sistema' : 'Sistemas' }}
        </h4>
    </div>

    <div class="card-body p-4">
        <form action="{{ $sistema->exists ? route('sistema.update', $sistema->id) : route('sistema.store') }}" method="POST" novalidate>
            @csrf
            @if($sistema->exists) @method('PUT') @endif

            <div class="row g-4">
                <div class="col-md-8">
                    <label class="form-label">Nombre del sistema: <span class="text-danger">*</span></label>
                    <input type="text" name="nombre_sistema" class="form-control @error('nombre_sistema') is-invalid @enderror" 
                           value="{{ old('nombre_sistema', $sistema->nombre_sistema) }}" placeholder="Ej. Sistema de Atención..." required>
                    @error('nombre_sistema') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Siglas del sistema: <span class="text-danger">*</span></label>
                    <input type="text" name="sigla_sistema" class="form-control @error('sigla_sistema') is-invalid @enderror" 
                           value="{{ old('sigla_sistema', $sistema->sigla_sistema) }}" placeholder="Ej. SAM" required>
                    @error('sigla_sistema') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                @if($sistema->exists)
                    <div class="col-md-8 mt-8">
                        <div class="form-check form-switch mb-2 p-3 rounded-3">
                            <input type="hidden" name="inactivo" id="inputInactivo" value="{{ $sistema->inactivo }}">
                            
                            <div class="d-flex align-items-center ms-4">
                                <input class="form-check-input mt-0" type="checkbox" role="switch" id="switchEstatus" 
                                    {{ $sistema->inactivo ? '' : 'checked' }} onchange="toggleEstatus()">
                                
                                <label class="form-check-label ms-3 fw-bold" for="switchEstatus" id="labelEstatus">
                                    {{ $sistema->inactivo ? 'Sistema inactivo' : 'Sistema activo' }}
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-start align-items-center gap-3 mt-3 pt-3">
                <button type="submit" class="btn btn-guinda rounded-pill shadow-sm">Guardar</button>
                <a href="{{ route('sistema.index') }}" class="btn-cancelar">Cancelar</a>
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
            labelElem.innerText = 'Sistema activo';
            labelElem.classList.replace('text-danger', 'text-success');
        } else {
            inputHidden.value = 'X';
            labelElem.innerText = 'Sistema inactivo';
            labelElem.classList.replace('text-success', 'text-danger');
        }
    }
</script>
@endsection