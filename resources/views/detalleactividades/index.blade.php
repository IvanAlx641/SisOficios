@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    
    <div class="card w-100 position-relative border-0 shadow-sm mb-4">
        <div class="card-body p-4 p-md-5">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0 text-guinda">Actividades</h3>
                <div class="d-flex gap-2">
                    <a href="{{ route('actividad.index') }}" class="btn btn-outline-guinda rounded-pill px-4 shadow-sm fw-bold">
                        <i class="ti ti-arrow-left me-1"></i>
                    </a>
                    <button type="button" class="btn btn-guinda rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalDetalleActividad" id="btnNuevoDetalle">
                         Agregar
                    </button>
                </div>
            </div>

            <ul class="nav nav-custom-tabs border-bottom mb-4">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('actividad.edit', $actividad->id) }}">
                        <i class="ti ti-file me-1"></i> Datos generales
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        <i class="ti ti-list-details me-1"></i> Detalle de las actividades
                    </a>
                </li>
            </ul>

            <div class="row mb-5 p-3 bg-light rounded-3 border">
                <div class="col-md-4 mb-2 mb-md-0">
                    <span class="text-muted small fw-semibold">Fecha de la actividad:</span><br>
                    <span class="text-dark">{{ $actividad->fecha_actividad ? $actividad->fecha_actividad->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <span class="text-muted small fw-semibold">Responsable:</span><br>
                    <span class="text-dark">{{ mb_strtoupper($actividad->responsable->nombre ?? 'N/A') }}</span>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small fw-semibold">Sistema:</span><br>
                    <span class="text-dark">{{ $actividad->sistema->sigla_sistema ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="row g-4 mt-2">
                @forelse ($detalleactividades as $detalle)
                    <div class="col-md-6 col-lg-4">
                        <div class="card task-card h-100 border-0 shadow-sm position-relative rounded-3 border-top border-4 border-guinda">
                            
                            <div class="custom-card-header text-center py-3 px-3  bg-white border-bottom">
                                <span class="badge {{ $detalle->estatus == 'Atendida' ? 'bg-success text-white' : 'bg-warning text-dark' }} rounded-pill px-3 py-1 fw-bold shadow-sm mb-2" style="font-size: 0.7rem;">
                                    {{ $detalle->estatus }}
                                </span>
                                <h6 class="text-white fw-bold mb-0 text-uppercase text-truncate d-block" style="font-size: 0.85rem;" title="{{ optional($detalle->tipoRequerimiento)->tipo_requerimiento ?? 'N/A' }}">
                                    {{ optional($detalle->tipoRequerimiento)->tipo_requerimiento ?? 'N/A' }}
                                </h6>
                            </div>

                            <div class="card-body bg-white pt-3 pb-3">
                                <small class="text-dark d-block fw-bold text-uppercase mb-2" style="font-size: 0.7rem;">Descripción:</small>
                                
                                    {!! $detalle->descripcion_actividad !!}
                                
                            </div>

                            <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center rounded-bottom">
                              
                                
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary text-guinda border-0 btn-editar-detalle" data-url="{{ route('detalleactividad.edit', $detalle->id) }}" data-action="{{ route('detalleactividad.update', $detalle->id) }}" title="Editar Tarea">
                                        <i class="ti ti-pencil fs-5"></i>
                                    </button>
                                    
                                    <form action="{{ route('detalleactividad.destroy', $detalle->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este detalle de tarea?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm text-guinda border-0" title="Eliminar"><i class="ti ti-trash fs-5"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-light text-center p-5 border shadow-sm">
                            <i class="ti ti-apps fs-1 text-muted d-block mb-2"></i>
                            <span class="text-muted fs-5">Aún no hay tareas/detalles registrados para esta actividad.</span>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-4 border-top pt-3 text-end">
                 {{ $detalleactividades->appends($request->all())->links() }}
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalDetalleActividad" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-header bg-light border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-guinda" id="modalDetalleTitle">Detalle de tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4 bg-white">
                <form action="{{ route('detalleactividad.store') }}" method="POST" id="formDetalle" novalidate>
                    @csrf
                    <div id="methodContainer"></div>
                    <input type="hidden" name="error_modal_id" value="modalDetalleActividad">

                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-bold text-guinda2 small">Tipo de requerimiento: <span class="text-danger">*</span></label>
                            <select name="tipo_requerimiento_id" id="modal_tipo_req" class="form-select bg-white border-guinda @error('tipo_requerimiento_id') is-invalid @enderror" required>
                                <option value="">Seleccione...</option>
                                @foreach($tipoRequerimiento as $id => $nombre)
                                    <option value="{{ $id }}" {{ old('tipo_requerimiento_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                            @error('tipo_requerimiento_id')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-guinda2 small d-block">Estatus: <span class="text-danger">*</span></label>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input border-guinda" type="radio" name="estatus" id="modalEstProceso" value="En proceso" {{ old('estatus', 'En proceso') == 'En proceso' ? 'checked' : '' }}>
                                <label class="form-check-label small" for="modalEstProceso">En proceso</label>
                            </div>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input border-guinda" type="radio" name="estatus" id="modalEstAtendida" value="Atendida" {{ old('estatus') == 'Atendida' ? 'checked' : '' }}>
                                <label class="form-check-label small" for="modalEstAtendida">Atendida</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-guinda2 small">Descripción de la actividad: <span class="text-danger">*</span></label>
                        <div class="bg-white border border-guinda rounded">
                            <div id="quillEditor" style="height: 150px; font-family: inherit;">{!! old('descripcion_actividad') !!}</div>
                        </div>
                        <input type="hidden" name="descripcion_actividad" id="hidden_descripcion" value="{{ old('descripcion_actividad') }}">
                        <div id="error_descripcion" class="text-danger fw-bold small mt-1" style="display: none;">La descripción de la actividad es obligatoria.</div>
                        @error('descripcion_actividad')
                            <div class="text-danger fw-bold small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center pt-3 border-top mt-2">
                        <button type="submit" class="btn btn-guinda rounded-pill px-5 shadow-sm fw-bold me-3 py-2" id="btnGuardarDetalle">Guardar</button>
                        <button type="button" class="btn-cancelar text-guinda text-decoration-none fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Paleta Institucional */
    
    .bg-guinda { background-color: #9D2449 !important; }
    .border-guinda { border-color: #9D2449 !important; }
    
    .btn-guinda { background-color: #9D2449; color: white; border: none; transition: 0.3s; }
    .btn-guinda:hover { background-color: #7a1c38; color: white; transform: translateY(-1px); }
    .btn-outline-guinda { color: #9D2449; border-color: #9D2449; background: transparent; transition: 0.3s;}
    .btn-outline-guinda:hover { background-color: #9D2449; color: white; }
    .btn-cancelar { background: transparent; border: none; padding: 0; transition: all 0.2s; }
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

    /* Estilo de la Tarea en Card Unificada */
     .custom-card {
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .custom-card-header {
        background: linear-gradient(135deg, #9D2449 0%, #c4305c 100%);
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    /* Quill */
    .ql-toolbar.ql-snow { border: none !important; border-bottom: 1px solid #9D2449 !important; background-color: #f8f9fa; border-top-left-radius: 0.375rem; border-top-right-radius: 0.375rem; }
    .ql-container.ql-snow { border: none !important; }
    .ql-editor { font-size: 0.9rem; color: #6c757d; font-family: inherit; }

    /* Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9D2449; }
</style>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        @if(old('error_modal_id'))
            var myModal = new bootstrap.Modal(document.getElementById("{{ old('error_modal_id') }}"));
            myModal.show();
        @endif

        var quill = new Quill('#quillEditor', {
            theme: 'snow',
            placeholder: 'Redacte los detalles de la tarea aquí...',
            modules: { toolbar: [['bold', 'italic', 'underline'], [{ 'list': 'bullet' }]] }
        });

        const form = document.getElementById('formDetalle');
        const hiddenDesc = document.getElementById('hidden_descripcion');
        const errorDesc = document.getElementById('error_descripcion');
        const methodContainer = document.getElementById('methodContainer');
        const modalTitle = document.getElementById('modalDetalleTitle');
        const originalAction = "{{ route('detalleactividad.store') }}";

        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            if(!document.getElementById('modal_tipo_req').value) {
                document.getElementById('modal_tipo_req').classList.add('is-invalid');
                isValid = false;
            } else {
                document.getElementById('modal_tipo_req').classList.remove('is-invalid');
            }

            var quillContent = quill.root.innerHTML.trim();
            if (quillContent === '<p><br></p>' || quillContent === '') {
                errorDesc.style.display = 'block';
                document.querySelector('.ql-container').style.border = '1px solid #dc3545';
                isValid = false;
            } else {
                errorDesc.style.display = 'none';
                document.querySelector('.ql-container').style.border = 'none';
                hiddenDesc.value = quillContent; 
            }

            if (!isValid) e.preventDefault();
        });

        document.querySelectorAll('.btn-editar-detalle').forEach(btn => {
            btn.addEventListener('click', function() {
                const urlDatos = this.getAttribute('data-url');
                const urlAction = this.getAttribute('data-action');

                fetch(urlDatos)
                    .then(response => response.json())
                    .then(data => {
                        modalTitle.textContent = "Editar Tarea";
                        form.action = urlAction;
                        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                        document.getElementById('modal_tipo_req').value = data.tipo_requerimiento_id;
                        if(data.estatus === 'En proceso') document.getElementById('modalEstProceso').checked = true;
                        if(data.estatus === 'Atendida') document.getElementById('modalEstAtendida').checked = true;
                        
                        quill.root.innerHTML = data.descripcion_actividad;
                        
                        var myModal = new bootstrap.Modal(document.getElementById('modalDetalleActividad'));
                        myModal.show();
                    });
            });
        });

        document.getElementById('modalDetalleActividad').addEventListener('hidden.bs.modal', function () {
            form.action = originalAction;
            modalTitle.textContent = "Agregar Detalle de Tarea";
            methodContainer.innerHTML = '';
            
            document.getElementById('modal_tipo_req').classList.remove('is-invalid');
            document.querySelectorAll('.invalid-feedback, #error_descripcion').forEach(err => err.style.display = 'none');
            
            form.reset();
            quill.root.innerHTML = '';
            document.querySelector('.ql-container').style.border = 'none';
        });
    });
</script>
@endsection