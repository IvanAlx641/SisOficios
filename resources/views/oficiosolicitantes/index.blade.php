@extends('layouts.admin')

@section('content')
    <div class="card border-0 shadow-sm rounded-3">
         <div class="card-body pt-3 pb-2 bg-light d-flex justify-content-between align-items-center border-bottom">
                <div class="d-flex align-items-center">
                    <h4 class="fw-bold mb-0 text-guinda">Registro</h4>
                    <a href="{{ route('oficio.index') }}" class="text-guinda text-decoration-none me-3" title="Volver al listado">
                        <i class="ti ti-arrow-back-up fs-3"></i>
                    </a>
                </div>
                <div class="col-6 text-end d-flex justify-content-end gap-2">

                    {{-- Si puede agregar (es conjunta, o no es conjunta y no hay ninguno), mostramos el botón --}}
                    @if ($puedeAgregar)
                        <div class="d-flex justify-content-end mb-4">
                            <button type="button" class="btn btn-guinda rounded-pill px-4 shadow-sm" data-bs-toggle="modal"
                                data-bs-target="#addSolicitanteModal">
                                Agregar
                            </button>
                        </div>
                    @endif
                </div>
            </div>














        <div class="card-body p-4 bg-white">
            
            <ul class="nav nav-tabs border-bottom-0">
                <li class="nav-item">
                    <a class="nav-link text-muted" href="{{ route('oficio.edit', $oficio->id) }}">
                        <i class="ti ti-file-description me-1"></i> Datos generales
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold text-guinda border-bottom-guinda" href="#">
                        <i class="ti ti-users me-1"></i> Solicitantes
                    </a>
                </li>
            </ul>
            <div class="row g-3">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <span class="small text-muted me-2">Número de oficio:</span>
                            <span class=" text-dark small mb-0">{{ $oficio->numero_oficio }}</span>
                            <a href="{{ $oficio->url_oficio }}" target="_blank"
                                class="ms-3 btn btn-sm btn-outline-guinda py-0 px-2 rounded-pill">
                                <i class="ti ti-eye"></i> Ver oficio
                            </a>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <span class="small text-muted me-2">Fecha de recepción:</span>
                            <span class="text-dark small mb-0">{{ $oficio->fecha_recepcion->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
                @forelse ($solicitantes as $solicitante)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-bold text-guinda text-truncate">{{ $solicitante->nombre }}</h6>
                                <hr class="opacity-25 my-2 border-guinda">

                                <p class="small text-muted mb-1">Dependencia</p>
                                <p class="fw-semibold small text-dark mb-2 text-truncate">
                                    {{ $solicitante->dependencia->nombre_dependencia ?? 'N/A' }}</p>

                                <p class="small text-muted mb-1">Cargo</p>
                                <p class="fw-semibold small text-dark mb-0 text-truncate">{{ $solicitante->cargo }}</p>

                                <div class="mt-3 text-end">
                                    <form action="{{ route('oficiosolicitante.destroy', $solicitante->id) }}"
                                        method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-guinda border-0">
                                            <i class="ti ti-trash fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="ti ti-user-off fs-1 text-muted mb-2"></i>
                        <h5 class="text-muted">No hay solicitantes asignados.</h5>
                    </div>
                @endforelse
            </div>


        </div>
    </div>

    <div class="modal fade" id="addSolicitanteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-guinda text-white">
                    <h5 class="modal-title fw-bold text-white">Agregar solicitante</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('oficiosolicitante.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold text-guinda">Solicitado por:</label>
                            <select name="solicitante_id" id="modal_solicitante_id" class="form-select" required>
                                <option value="">Seleccione una persona...</option>
                                @foreach ($listaSolicitantes as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-start align-items-center gap-3 mt-5 pt-3 border-top">
                            <button type="submit" class="btn btn-guinda rounded-pill px-4 shadow-sm">Guardar</button>
                            <a href="{{ route('oficiosolicitante.index') }}" class="btn-cancelar">Cancelar</a>

                        </div>
                    </form>
                </div>
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
            // Activar buscador en el modal
            convertirSelectABuscador('modal_solicitante_id');
        });
    </script>
@endsection
