@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card w-100 border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom pb-3 pt-4 d-flex justify-content-between align-items-center">
            <div>
                <a href="javascript:history.back()" class="text-guinda text-decoration-none me-2" title="Volver">
                    <i class="ti ti-arrow-back-up fs-3"></i>
                </a>
                <h4 class="fw-bold mb-0 text-guinda d-inline-block">Oficio: {{ $oficio->numero_oficio }}</h4>
            </div>
            <span class="badge bg-success rounded-pill px-3 py-2 fs-6">Atendido</span> </div>

        <div class="card-body p-4">
            
            <h5 class="text-guinda fw-bold mb-3 border-bottom pb-2">Datos Generales</h5>
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block fw-bold">Ver documento oficial:</small>
                    <a href="{{ $oficio->url_documento ?? '#' }}" target="_blank" class="text-primary fs-4"><i class="ti ti-file-text"></i></a>
                </div>
                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block fw-bold">Fecha de recepción:</small>
                    <span class="text-dark">{{ $oficio->fecha_recepcion ? $oficio->fecha_recepcion->format('d/m/Y') : 'N/A' }}</span>
                </div>
                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block fw-bold">Dirigido a:</small>
                    <span class="text-dark">{{ $oficio->dirigidoA->nombre ?? 'N/A' }}</span>
                </div>
                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block fw-bold">Asignado a (Área):</small>
                    <span class="text-dark">Subdirección de Aplicaciones Digitales</span> </div>
                <div class="col-md-8 mb-3">
                    <small class="text-muted d-block fw-bold">Descripción breve del requerimiento:</small>
                    <span class="text-dark">{{ $oficio->descripcion_breve ?? 'Sin descripción' }}</span>
                </div>
                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block fw-bold">Solicitud conjunta:</small>
                    <span class="text-dark">{{ $oficio->es_conjunta ? 'Sí' : 'No' }}</span>
                </div>
            </div>

            <h5 class="text-guinda fw-bold mb-3 border-bottom pb-2 mt-4">Solicitantes</h5>
            <div class="row mb-4">
                @foreach($oficio->solicitantes as $sol)
                <div class="col-md-12 mb-3 bg-light p-3 rounded">
                    <div class="row">
                        <div class="col-md-6 mb-2"><small class="text-muted fw-bold">Solicitado por:</small> <span class="text-dark">{{ $sol->nombre }}</span></div>
                        <div class="col-md-6 mb-2"><small class="text-muted fw-bold">Dependencia:</small> <span class="text-dark">SECRETARÍA DE LA CONTRALORÍA</span></div>
                        <div class="col-md-6 mb-2"><small class="text-muted fw-bold">Unidad Administrativa:</small> <span class="text-dark">DIRECCIÓN DE INVESTIGACIÓN</span></div>
                        <div class="col-md-6 mb-2"><small class="text-muted fw-bold">Cargo:</small> <span class="text-dark">ENCARGADO DE DESPACHO</span></div>
                    </div>
                </div>
                @endforeach
            </div>

            <h5 class="text-guinda fw-bold mb-3 border-bottom pb-2 mt-4">Datos del turno</h5>
            <div class="row mb-4">
                <div class="col-md-4 mb-3"><small class="text-muted d-block fw-bold">Fecha de turno:</small><span class="text-dark">19/12/2025</span></div>
                <div class="col-md-4 mb-3"><small class="text-muted d-block fw-bold">Sistema:</small><span class="text-dark">{{ $oficio->sistema->nombre_sistema ?? 'N/A' }}</span></div>
                <div class="col-md-4 mb-3"><small class="text-muted d-block fw-bold">Tipo de requerimiento:</small><span class="text-dark">{{ $oficio->tipo_requerimiento }}</span></div>
                <div class="col-md-8 mb-3"><small class="text-muted d-block fw-bold">Observaciones:</small><span class="text-dark">---</span></div>
                <div class="col-md-4 mb-3"><small class="text-muted d-block fw-bold">Responsable(s):</small><span class="text-dark">ASG</span></div>
            </div>

            <h5 class="text-guinda fw-bold mb-3 border-bottom pb-2 mt-4">Oficios de Respuesta</h5>
            @if($oficio->respuestasOficios && $oficio->respuestasOficios->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Número</th>
                                <th>Fecha</th>
                                <th>Firmado por</th>
                                <th class="text-center">Acuse</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($oficio->respuestasOficios as $resp)
                            <tr>
                                <td>{{ $resp->numero_oficio_respuesta }}</td>
                                <td>{{ $resp->fecha_respuesta ? $resp->fecha_respuesta->format('d/m/Y') : '' }}</td>
                                <td>{{ $resp->firmadoPor->nombre ?? '' }}</td>
                                <td class="text-center">
                                    <a href="{{ $resp->url_oficio_respuesta ?? '#' }}" class="text-primary fs-4" target="_blank"><i class="ti ti-eye"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No hay oficios de respuesta registrados.</p>
            @endif

        </div>
    </div>
</div>

<style>
    .bg-guinda { background-color: #9D2449 !important; }
    .border-guinda { border-color: #9D2449 !important; }
    .text-guinda { color: #9D2449 !important; }
</style>
@endsection