@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    
    <div class="card w-100 position-relative border-0 shadow-sm mb-4">
        <div class="card-body p-4 p-md-5">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0 text-guinda">Actividades</h3>
                <a href="{{ route('actividad.index') }}" class="btn btn-outline-guinda rounded-pill px-4 shadow-sm fw-bold">
                    <i class="ti ti-arrow-left me-1"></i>
                </a>
            </div>

            <ul class="nav nav-custom-tabs border-bottom mb-4">
                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        <i class="ti ti-file me-1"></i> Datos generales
                    </a>
                </li>
                <li class="nav-item">
                    @if($actividad->exists)
                        <a class="nav-link" href="{{ route('detalleactividad.index', ['actividad_id' => encrypt($actividad->id)]) }}">
                            <i class="ti ti-list-details me-1"></i> Detalle de las actividades
                        </a>
                    @else
                        <a class="nav-link disabled text-muted" href="#" tabindex="-1" aria-disabled="true" title="Guarde los datos generales primero">
                            <i class="ti ti-list-details me-1"></i> Detalle de las actividades
                        </a>
                    @endif
                </li>
            </ul>

            <div class="p-3">
                <form action="{{ $actividad->exists ? route('actividad.update', $actividad->id) : route('actividad.store') }}" method="POST" novalidate>
                    @csrf
                    @if($actividad->exists)
                        @method('PUT')
                    @endif

                    @if($errors->has('fecha_actividad') && str_contains($errors->first('fecha_actividad'), 'Ya existe'))
                        <div class="alert alert-danger d-flex align-items-center mb-4 p-3 shadow-sm border-0">
                            <i class="ti ti-alert-triangle fs-4 me-2"></i>
                            <div class="fw-bold">{{ $errors->first('fecha_actividad') }}</div>
                        </div>
                    @endif

                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-guinda2 small">Fecha: <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_actividad" class="form-control border-guinda  @error('fecha_actividad') is-invalid @enderror" value="{{ old('fecha_actividad', optional($actividad->fecha_actividad)->format('Y-m-d') ?? date('Y-m-d')) }}" required>
                            @error('fecha_actividad')
                                <span class="invalid-feedback fw-bold">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-guinda2 small">Responsable: <span class="text-danger">*</span></label>
                            <select name="responsable_id" class="form-select border-guinda @error('responsable_id') is-invalid @enderror" required {{ isset($isResponsable) && $isResponsable ? 'readonly' : '' }}>
                                @if(!isset($isResponsable) || !$isResponsable)
                                    <option value="">Seleccione un responsable...</option>
                                @endif
                                @foreach($responsables as $id => $nombre)
                                    <option value="{{ $id }}" {{ old('responsable_id', $actividad->responsable_id) == $id ? 'selected' : '' }}>{{ mb_strtoupper($nombre) }}</option>
                                @endforeach
                            </select>
                            @error('responsable_id')
                                <span class="invalid-feedback fw-bold">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-guinda2 small">Sistema: <span class="text-danger">*</span></label>
                            <select name="sistema_id" class="form-select border-guinda @error('sistema_id') is-invalid @enderror" required>
                                <option value="">Seleccione un sistema...</option>
                                @foreach($sistemas as $id => $sigla)
                                    <option value="{{ $id }}" {{ old('sistema_id', $actividad->sistema_id) == $id ? 'selected' : '' }}>{{ mb_strtoupper($sigla) }}</option>
                                @endforeach
                            </select>
                            @error('sistema_id')
                                <span class="invalid-feedback fw-bold">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex align-items-center pt-2">
                        <button type="submit" class="btn btn-guinda rounded-pill px-5 shadow-sm fw-bold me-3 py-2">
                            Guardar
                        </button>
                        <a href="{{ route('actividad.index') }}" class="text-guinda text-decoration-none fw-semibold btn-cancelar">Cancelar</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
   
    .bg-guinda { background-color: #9D2449 !important; }
    .border-guinda { border-color: #9D2449 !important; }
    
    .btn-guinda { background-color: #9D2449; color: white; border: none; transition: 0.3s; }
    .btn-guinda:hover { background-color: #7a1c38; color: white; transform: translateY(-1px); }
    .btn-outline-guinda { color: #9D2449; border-color: #9D2449; background: transparent; transition: 0.3s;}
    .btn-outline-guinda:hover { background-color: #9D2449; color: white; }
    .btn-cancelar { transition: all 0.2s; }
    .btn-cancelar:hover { text-decoration: underline !important; }

    /* Estilo de Pestañas (Tabs) */
    .nav-custom-tabs { display: flex; list-style: none; padding-left: 0; margin-bottom: 0; }
    .nav-custom-tabs .nav-link { 
        color: #6c757d; font-weight: 500; padding: 0.75rem 1.5rem; border-bottom: 3px solid transparent; transition: all 0.3s; 
    }
    .nav-custom-tabs .nav-link:hover:not(.disabled) { color: #9D2449; }
    .nav-custom-tabs .nav-link.active { 
        color: #212529; font-weight: 700; border-bottom-color: #212529; 
    }
</style>
@endsection