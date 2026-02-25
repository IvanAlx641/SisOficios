@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-4 text-center border-bottom bg-light d-flex flex-column align-items-center justify-content-center position-relative">
            
            <h5 class="fw-bold text-guinda mb-0">Oficio: {{ $oficio->numero_oficio }}</h5>
            
            <div class="position-absolute end-0 me-4">
                @php
                    $badgeClass = match($oficio->estatus) {
                        'Pendiente' => 'bg-warning text-dark',
                        'Turnado' => 'bg-info text-white',
                        'Concluido', 'Atendido' => 'bg-success text-white',
                        'Cancelado' => 'bg-danger text-white',
                        default => 'bg-secondary text-white',
                    };
                @endphp
                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2 fs-6">{{ $oficio->estatus ?? 'Atendido' }}</span>
            </div>
        </div>

        <div class="card-body p-5 pt-4">
            
            <div class="row mb-4">
                <div class="col-12 mb-3 d-flex align-items-center">
                    <span class="text-muted small fw-semibold me-2">Ver oficio:</span> 
                    @if($oficio->url_oficio)
                        <a href="{{ asset($oficio->url_oficio) }}" class="text-guinda fs-5" target="_blank" title="Ver documento PDF"><i class="ti ti-eye"></i></a>
                    @else
                        <span class="text-muted small"><i class="ti ti-eye-off"></i> No adjunto</span>
                    @endif
                </div>
                
                <div class="col-12 mb-2">
                    <span class="text-muted small fw-semibold">Fecha de recepción:</span> 
                    <span class="text-dark small">{{ $oficio->fecha_recepcion ? \Carbon\Carbon::parse($oficio->fecha_recepcion)->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="col-12 mb-2">
                    <span class="text-muted small fw-semibold">Dirigido a:</span> 
                    <span class="text-dark small text-uppercase">{{ $oficio->areaDirigido->nombre_unidad_administrativa ?? 'N/A' }}</span>
                </div>
                <div class="col-12 mb-2">
                    <span class="text-muted small fw-semibold">Asignar a:</span> 
                    <span class="text-dark small text-uppercase">{{ $oficio->areaAsignada->nombre_unidad_administrativa ?? 'N/A' }}</span>
                </div>
                <div class="col-12 mb-2">
                    <span class="text-muted small fw-semibold">Descripción breve del requerimiento:</span> 
                    <span class="text-dark small">{{ $oficio->descripción_oficio ?? '-' }}</span>
                </div>
                <div class="col-12 mb-4">
                    <span class="text-muted small fw-semibold">Solicitud conjunta:</span> 
                    <span class="text-dark small">{{ $oficio->solicitud_conjunta == 'Sí' ? 'Sí' : 'No' }}</span>
                </div>
            </div>

            <h6 class="fw-bold text-guinda border-bottom pb-2 mb-3">Solicitantes</h6>
            <div class="row mb-4">
                @forelse($oficio->solicitantes as $sol)
                    <div class="col-12 mb-3">
                        <div class="mb-1"><span class="text-muted small fw-semibold">Solicitado por:</span> <span class="text-dark small text-uppercase">{{ $sol->nombre }}</span></div>
                        <div class="mb-1"><span class="text-muted small fw-semibold">Dependencia:</span> <span class="text-dark small text-uppercase">{{ optional($sol->dependencia)->NOMBRE_DEPENDENCIA ?? 'SECRETARÍA DE LA CONTRALORÍA' }}</span></div>
                        <div class="mb-1"><span class="text-muted small fw-semibold">Unidad Administrativa:</span> <span class="text-dark small text-uppercase">{{ optional($sol->unidadAdministrativa)->nombre_unidad_administrativa ?? '-' }}</span></div>                        <div class="mb-1"><span class="text-muted small fw-semibold">Cargo:</span> <span class="text-dark small text-uppercase">{{ $sol->cargo ?? '-' }}</span></div>
                    </div>
                @empty
                    <div class="col-12"><p class="text-muted small fst-italic">No hay solicitantes registrados.</p></div>
                @endforelse
            </div>

            <h6 class="fw-bold text-guinda border-bottom pb-2 mb-3">Datos del turno</h6>
            <div class="row mb-4">
                <div class="col-12 mb-2">
                    <span class="text-muted small fw-semibold">Fecha de turno:</span> 
                    <span class="text-dark small">{{ $oficio->fecha_turno ? \Carbon\Carbon::parse($oficio->fecha_turno)->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="col-12 mb-2">
                    <span class="text-muted small fw-semibold">Sistema:</span> 
                    <span class="text-dark small">{{ $oficio->sistema->nombre_sistema ?? 'N/A' }}</span>
                </div>
                <div class="col-12 mb-2">
                    <span class="text-muted small fw-semibold">Tipo de requerimiento:</span> 
                    <span class="text-dark small">{{ optional($oficio->tipoRequerimiento)->tipo_requerimiento ?? '-' }}</span>
                </div>
                <div class="col-12 mb-2">
                    <span class="text-muted small fw-semibold">Observaciones:</span> 
                    <span class="text-dark small">{{ $oficio->observaciones_turno ?? '---' }}</span>
                </div>
                <div class="col-12 mb-3">
                    <span class="text-muted small fw-semibold">Responsable(s):</span> 
                    <span class="text-guinda small fw-bold text-uppercase">
                        @forelse($oficio->responsablesOficios as $resp)
                            {{ $resp->responsable->nombre ?? 'ASG' }}@if(!$loop->last), @endif
                        @empty
                            ---
                        @endforelse
                    </span>
                </div>
            </div>

            <h6 class="fw-bold text-guinda border-bottom pb-2 mb-3">Seguimientos</h6>
            <div class="row mb-4">
                @php
                    $seguimientos = collect();
                    foreach($oficio->responsablesOficios as $resp) {
                        $seguimientos = $seguimientos->merge($resp->seguimientos);
                    }
                    $seguimientos = $seguimientos->sortByDesc('fecha_creacion');
                @endphp

                @forelse($seguimientos as $seg)
                    <div class="col-12 mb-3">
                        <div class="mb-1"><span class="text-muted small fw-semibold">Fecha:</span> <span class="text-dark small">{{ $seg->fecha_seguimiento ? \Carbon\Carbon::parse($seg->fecha_seguimiento)->format('d/m/Y') : '-' }}</span></div>
                        <div class="mb-1"><span class="text-muted small fw-semibold">Estatus:</span> <span class="text-guinda small fw-semibold">{{ $seg->estatus }}</span></div>
                        <div class="mb-1"><span class="text-muted small fw-semibold">Observaciones:</span> <span class="text-dark small">{{ $seg->observaciones ?? '---' }}</span></div>
                    </div>
                @empty
                    <div class="col-12"><p class="text-muted small fst-italic">No hay seguimientos registrados.</p></div>
                @endforelse
            </div>

            <h6 class="fw-bold text-guinda border-bottom pb-2 mb-3">Datos de la conclusión</h6>
            <div class="row mb-4">
                <div class="col-12 mb-2">
                    <span class="text-muted small fw-semibold">Fecha de conclusión:</span> 
                    <span class="text-dark small">{{ $oficio->fecha_conclusion ? \Carbon\Carbon::parse($oficio->fecha_conclusion)->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="col-12 mb-2 d-flex align-items-center">
                    <span class="text-muted small fw-semibold me-2">Soporte documental:</span> 
                    @if($oficio->soporte_documental)
                        <a href="{{ asset('storage/'.$oficio->soporte_documental) }}" target="_blank" class="text-guinda fs-5" title="Ver soporte"><i class="ti ti-eye"></i></a>
                    @else
                        <span class="text-muted small"><i class="ti ti-eye-off"></i></span>
                    @endif
                </div>
                <div class="col-12 mb-3">
                    <span class="text-muted small fw-semibold">Texto propuesta para la respuesta:</span> 
                    <span class="text-dark small">{{ $oficio->propuesta_respuesta ?? '---' }}</span>
                </div>
            </div>

            <h6 class="fw-bold text-guinda border-bottom pb-2 mb-3">Datos del oficio de respuesta</h6>
            <div class="row mb-3">
                @forelse($oficio->respuestasOficios as $respuesta)
                    <div class="col-md-6 mb-3">
                        <div class="mb-2"><span class="text-muted small fw-semibold">Fecha de respuesta:</span> <span class="text-dark small">{{ $respuesta->fecha_respuesta ? \Carbon\Carbon::parse($respuesta->fecha_respuesta)->format('d/m/Y') : '-' }}</span></div>
                        <div class="mb-2"><span class="text-muted small fw-semibold">Dirigido a:</span> <span class="text-dark small text-uppercase">{{ $respuesta->dirigidoA->nombre ?? '---' }}</span></div>
                        <div class="mb-2"><span class="text-muted small fw-semibold">Firmado por:</span> <span class="text-guinda small text-uppercase fw-semibold">{{ $respuesta->firmadoPor->nombre ?? '---' }}</span></div>
                        <div class="mb-2"><span class="text-muted small fw-semibold">Descripción breve de la respuesta:</span> <span class="text-dark small">{{ $respuesta->descripción_respuesta_oficio ?? '---' }}</span></div>
                        <div class="mb-2 d-flex align-items-center">
                            <span class="text-muted small fw-semibold me-2">Ver acuse de respuesta:</span> 
                            @if($respuesta->url_oficio_respuesta)
                                <a href="{{ asset('storage/'.$respuesta->url_oficio_respuesta) }}" target="_blank" class="text-guinda fs-5"><i class="ti ti-eye"></i></a>
                            @else
                                <span class="text-muted small"><i class="ti ti-eye-off"></i></span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="mb-2"><span class="text-muted small fw-semibold">Número del oficio de respuesta:</span> <span class="text-dark small">{{ $respuesta->numero_oficio_respuesta }}</span></div>
                    </div>
                    @if(!$loop->last) <div class="col-12"><hr class="text-muted opacity-25"></div> @endif
                @empty
                    <div class="col-12"><p class="text-muted small fst-italic mb-0">---</p></div>
                @endforelse
            </div>

            <div class="text-end border-top pt-4 mt-2">
                <a href="{{ route('buscador.index') }}" class="btn btn-outline-guinda rounded-pill px-4 shadow-sm">
                    Regresar
                </a>
            </div>

        </div>
    </div>
</div>
@endsection