@extends('layouts.admin')

@section('content')

    <div class="card border-0 shadow-sm rounded-3">

        <div class="card-header bg-light border-bottom-0 pt-4 px-4">
            <h4 class="fw-bold text-guinda mb-3">
                Turno
            </h4>

            <ul class="nav nav-tabs border-bottom-0">
                <li class="nav-item">
                    <a class="nav-link active fw-bold text-guinda border-bottom-guinda" href="#">
                        <i class="ti ti-file-description me-1"></i> Datos del turno
                    </a>
                </li>
                <li class="nav-item">
                    @if ($oficio->estatus == 'Turnado')
                        <a class="nav-link text-muted"
                            href="{{ route('responsable.index', ['oficio_id' => encrypt($oficio->id)]) }}">
                            <i class="ti ti-users me-1"></i> Responsables
                        </a>
                    @else
                        <a class="nav-link disabled text-muted" href="#" tabindex="-1" aria-disabled="true"
                            title="El oficio debe estar en estatus 'Turnado'">
                            <i class="ti ti-users me-1"></i> Responsables <small>(Requiere Turnar)</small>
                        </a>
                    @endif
                </li>
            </ul>
        </div>

        <div class="card-body p-4 bg-light">
            <div class="bg-white p-4 rounded-3 shadow-sm border mb-4">
                <h5 class="fw-bold text-guinda mb-3 border-bottom pb-2">Contexto del oficio</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="mb-1 small text-muted">Número de oficio:</p>
                        <p class="fw-bold fs-5 text-dark mb-0">
                            {{ $oficio->numero_oficio }}
                            <a href="{{ $oficio->url_oficio }}" target="_blank"
                                class="ms-2 btn btn-sm btn-outline-guinda py-0 px-2 rounded-pill">
                                <i class="ti ti-eye"></i> Ver oficio
                            </a>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <p class="mb-1 small text-muted">Fecha de recepción:</p>
                        <p class="fw-semibold text-dark mb-0">{{ $oficio->fecha_recepcion->format('d/m/Y') }}</p>
                    </div>

                    <div class="col-md-12">
                        <p class="mb-1 small text-muted">Dirigido a:</p>
                        <p class="fw-semibold text-dark mb-0">
                            {{ optional($oficio->areaDirigido)->nombre_unidad_administrativa ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-12">
                        <p class="mb-1 small text-muted">Descripción del requerimiento:</p>
                        <p class="text-dark bg-light p-2 rounded border">{{ $oficio->descripción_oficio }}</p>
                    </div>

                    <div class="col-md-12">
                        <p class="mb-1 small text-muted">Solicitantes:</p>
                        @if ($oficio->solicitantes->count() > 0)
                            <ul class="list-unstyled mb-0 bg-light p-2 rounded border">
                                @foreach ($oficio->solicitantes as $solicitante)
                                    <li>
                                        <i class="ti ti-user text-guinda me-1"></i>
                                        <span class="fw-bold">{{ $solicitante->nombre }}</span>
                                        <span class="text-muted small">({{ $solicitante->cargo }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted fst-italic mb-0">Sin solicitantes asignados.</p>
                        @endif
                    </div>
                </div>
                <h4 class="fw-bold mb-3 mt-5">Turno</h4>

                <form action="{{ route('turno.update', $oficio->id) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-guinda2">Sistema <span
                                    class="text-danger">*</span></label>
                            <select name="sistema_id" id="sistema_id"
                                class="form-select border-guinda @error('sistema_id') is-invalid @enderror" required>
                                <option value="0">Seleccione un sistema...</option>
                                @foreach ($sistemas as $id => $nombre)
                                    <option value="{{ $id }}"
                                        {{ old('sistema_id', $oficio->sistema_id) == $id ? 'selected' : '' }}>
                                        {{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sistema_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-guinda2">Tipo de requerimiento <span
                                    class="text-danger">*</span></label>
                            <select name="tipo_requerimiento_id" id="tipo_requerimiento_id"
                                class="form-select border-guinda @error('tipo_requerimiento_id') is-invalid @enderror"
                                required>
                                <option value="0">Seleccione un tipo...</option>
                                @foreach ($tiposRequerimientos as $id => $nombre)
                                    <option value="{{ $id }}"
                                        {{ old('tipo_requerimiento_id', $oficio->tipo_requerimiento_id) == $id ? 'selected' : '' }}>
                                        {{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_requerimiento_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mt-4">
                            <label class="form-label fw-bold text-guinda2">Cambiar al estatus: <span
                                    class="text-danger">*</span></label>
                            <div class="mt-1 mb-3">

                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="estatus" value="Turnado"
                                        id="estatus_turnado"
                                        {{ old('estatus', $oficio->estatus) == 'Turnado' ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-info px-4" for="estatus_turnado">Turnado</label>

                                    <input type="radio" class="btn-check" name="estatus" value="Cancelado"
                                        id="estatus_cancelado"
                                        {{ old('estatus', $oficio->estatus) == 'Cancelado' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger px-4" for="estatus_cancelado">Cancelado</label>

                                    <input type="radio" class="btn-check" name="estatus" value="Atendido"
                                        id="estatus_atendido"
                                        {{ old('estatus', $oficio->estatus) == 'Atendido' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success px-4" for="estatus_atendido">Atendido</label>
                                </div>

                                @error('estatus')
                                    <span class="text-danger ms-2 align-middle"
                                        style="font-size: 0.875em;">{{ $message }}</span>
                                @enderror

                            </div>
                            @error('estatus')
                                <div class="invalid-feedback d-block mt-2 fw-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold text-guinda2 ">Observaciones</label>
                            <textarea name="observaciones_turno"
                                class="form-control border-guinda @error('observaciones_turno') is-invalid @enderror" rows="3"
                                placeholder="Escriba las observaciones del turno...">{{ old('observaciones_turno', $oficio->observaciones_turno) }}</textarea>
                            @error('observaciones_turno')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="d-flex justify-content-start align-items-center gap-3 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-guinda rounded-pill px-5 shadow-sm">
                            Guardar
                        </button>
                        <a href="{{ route('turno.index') }}" class="btn-cancelar">Cancelar</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <style>
        .border-bottom-guinda {
            border-bottom: 3px solid #9D2449 !important;
            background-color: transparent !important;
        }

        .nav-tabs .nav-link:hover {
            color: #9D2449;
            border-color: transparent;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Activamos los buscadores si los necesitas (Opcional para estos combos pequeños, pero recomendado para mantener el estándar)
            if (typeof convertirSelectABuscador === 'function') {
                convertirSelectABuscador('sistema_id');
                convertirSelectABuscador('tipo_requerimiento_id');
            }
        });
    </script>

@endsection
