@extends('layouts.admin')

@section('content')

<style>
    .btn-guinda { background-color: #9D2449; color: white; font-weight: 600; padding: 0.6rem 2rem; }
    .btn-guinda:hover { background-color: #801d3a; color: white; }
    .btn-cancelar { color: #9D2449; font-weight: 600; text-decoration: none; }
    .btn-cancelar:hover { text-decoration: underline; color: #801d3a; }

    .invalid-feedback { font-size: 0.875em; color: #dc3545; display: block; }
    .form-control.is-invalid { border-color: #dc3545; }
</style>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
        <h4 class="fw-bold" style="color: #9D2449;">
            {{ $solicitante->exists ? 'Editar solicitante' : 'Registrar nuevo solicitante' }}
        </h4>
        <p class="text-muted small">Campos con <span class="text-danger">*</span> son obligatorios.</p>
    </div>

    <div class="card-body p-4">
        <form action="{{ $solicitante->exists ? route('solicitantes.update', $solicitante->id) : route('solicitantes.store') }}" method="POST" novalidate>
            @csrf
            @if($solicitante->exists) @method('PUT') @endif

            <div class="row g-4">
                {{-- Nombre --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted small">Nombre completo <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $solicitante->nombre) }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Cargo --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted small">Cargo / Puesto <span class="text-danger">*</span></label>
                    <input type="text" name="cargo" class="form-control @error('cargo') is-invalid @enderror" value="{{ old('cargo', $solicitante->cargo) }}" required>
                    @error('cargo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Unidad Administrativa --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted small">Unidad Administrativa <span class="text-danger">*</span></label>
                    <select name="unidad_administrativa_id" class="form-select @error('unidad_administrativa_id') is-invalid @enderror" required>
                        <option value="">Seleccione una unidad</option>
                        @foreach($unidades as $id => $nombre)
                            <option value="{{ $id }}" {{ old('unidad_administrativa_id', $solicitante->unidad_administrativa_id) == $id ? 'selected' : '' }}>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('unidad_administrativa_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Estatus (Switch similar a Usuarios) --}}
                @if($solicitante->exists)
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="inactivo" id="inputInactivo" value="{{ $solicitante->inactivo }}">
                        <input class="form-check-input" type="checkbox" id="switchEstatus" {{ $solicitante->inactivo ? '' : 'checked' }} onchange="toggleEstatus()">
                        <label class="form-check-label ms-2 fw-bold" for="switchEstatus" id="labelEstatus">
                            {{ $solicitante->inactivo ? 'Solicitante inactivo' : 'Solicitante activo' }}
                        </label>
                    </div>
                </div>
                @endif
            </div>

            <div class="d-flex gap-3 mt-5 pt-3">
                <button type="submit" class="btn btn-guinda rounded-pill">Guardar Registro</button>
                <a href="{{ route('solicitantes.index') }}" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleEstatus() {
        const sw = document.getElementById('switchEstatus');
        const hidden = document.getElementById('inputInactivo');
        const label = document.getElementById('labelEstatus');
        
        if(sw.checked) {
            hidden.value = ''; label.innerText = 'Solicitante activo';
            label.classList.replace('text-danger', 'text-success');
        } else {
            hidden.value = 'X'; label.innerText = 'Solicitante inactivo';
            label.classList.add('text-danger');
        }
    }
</script>
@endsection