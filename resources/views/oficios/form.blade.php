@extends('layouts.admin')

@section('content')

<div class="card border-0 shadow-sm rounded-3">
    
    <div class="card-header bg-light border-bottom-0 pt-4 px-4">
        <h4 class="fw-bold text-guinda mb-3">
            {{ $oficio->exists ? 'Oficio: ' . $oficio->numero_oficio : 'Oficio' }}
        </h4>

        <ul class="nav nav-tabs border-bottom-0">
            <li class="nav-item">
                <a class="nav-link active fw-bold text-guinda border-bottom-guinda" href="#">
                    <i class="ti ti-file-description me-1"></i> Datos generales
                </a>
            </li>
            <li class="nav-item">
                @if($oficio->exists)
                    <a class="nav-link text-muted" href="{{ route('oficiosolicitante.index') }}">
                        <i class="ti ti-users me-1"></i> Solicitantes
                    </a>
                @else
                    <a class="nav-link disabled text-muted" href="#" tabindex="-1" aria-disabled="true">
                        <i class="ti ti-users me-1"></i> Solicitantes
                    </a>
                @endif
            </li>
        </ul>
    </div>

    <div class="card-body p-4 bg-white">
        
        <form action="{{ $oficio->exists ? route('oficio.update', $oficio->id) : route('oficio.store') }}" method="POST" novalidate>
            @csrf
            @if($oficio->exists) @method('PUT') @endif

            <div class="row g-4">
                
                <div class="col-md-4">
                    <label class="form-label fw-bold text-guinda2">Número de oficio <span class="text-danger">*</span></label>
                    <input type="text" name="numero_oficio" class="form-control @error('numero_oficio') is-invalid @enderror" 
                           value="{{ old('numero_oficio', $oficio->numero_oficio) }}" required>
                    @error('numero_oficio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-guinda2">Fecha recepción <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_recepcion" class="form-control @error('fecha_recepcion') is-invalid @enderror" 
                           value="{{ old('fecha_recepcion', optional($oficio->fecha_recepcion)->format('Y-m-d')) }}" required>
                    @error('fecha_recepcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold text-guinda2">Dirigido a <span class="text-danger">*</span></label>
                    <select name="dirigido_id" id="dirigido_id" class="form-select @error('dirigido_id') is-invalid @enderror" required>
                        <option value="">Seleccione una unidad...</option>
                        @foreach($unidades as $id => $nombre)
                            <option value="{{ $id }}" {{ old('dirigido_id', $oficio->dirigido_id) == $id ? 'selected' : '' }}>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('dirigido_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold text-guinda2">Asignar a <span class="text-danger">*</span></label>
                    <select name="area_asignada_id" id="area_asignada_id" class="form-select @error('area_asignada_id') is-invalid @enderror" required>
                        <option value="">Seleccione una unidad...</option>
                        @foreach($unidades as $id => $nombre)
                            <option value="{{ $id }}" {{ old('area_asignada_id', $oficio->area_asignada_id) == $id ? 'selected' : '' }}>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('area_asignada_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold text-guinda2">Descripción del requerimiento <span class="text-danger">*</span></label>
                    <textarea name="descripción_oficio" class="form-control @error('descripción_oficio') is-invalid @enderror" rows="3" required>{{ old('descripción_oficio', $oficio->descripción_oficio) }}</textarea>
                    @error('descripción_oficio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold text-guinda2">URL del Oficio (PDF) <span class="text-danger">*</span></label>
                    <input type="url" name="url_oficio" class="form-control @error('url_oficio') is-invalid @enderror" 
                           placeholder="https://..." value="{{ old('url_oficio', $oficio->url_oficio) }}" required>
                    @error('url_oficio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="solicitud_conjunta" id="solicitud_conjunta" value="X" 
                            {{ old('solicitud_conjunta', $oficio->solicitud_conjunta) == 'X' ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-guinda2" for="solicitud_conjunta">
                            ¿Es solicitud conjunta?
                        </label>
                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-start align-items-center gap-3 mt-5 pt-3 border-top">
                <button type="submit" class="btn btn-guinda rounded-pill px-4 shadow-sm">
                    {{ $oficio->exists ? 'Guardar' : 'Guardar' }}
                </button>
                <a href="{{ route('oficio.index') }}" class="btn-cancelar">Cancelar</a>
            </div>

        </form>
    </div>
</div>

<style>
    .border-bottom-guinda { border-bottom: 3px solid #9D2449 !important; background-color: transparent !important; }
    .nav-tabs .nav-link:hover { color: #9D2449; border-color: transparent; }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        convertirSelectABuscador('dirigido_id');
        convertirSelectABuscador('area_asignada_id');
    });
</script>

@endsection