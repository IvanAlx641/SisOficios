@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-3">
        {{-- Header con Título --}}
        <div class="card-header bg-light border-bottom-0 pt-4 px-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold text-guinda mb-0">Actividades
                    <a href="{{ route('actividad.index') }}" class="text-guinda text-decoration-none gap-3 me-2" title="Volver al listado">
                        <i class="ti ti-arrow-back-up fs-3"></i>
                    </a>
                </h4>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-guinda rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalDetalleActividad">
                        Agregar
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-4 bg-white">
            {{-- Tabs con el mismo estilo que Datos Generales --}}
            <ul class="nav nav-tabs border-bottom-0">
                <li class="nav-item">
                    <a class="nav-link text-muted" href="{{ route('actividad.edit', $actividad->id) }}">
                        <i class="ti ti-file-description me-1"></i> Datos generales
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold text-guinda border-bottom-guinda" href="#">
                        <i class="ti ti-list-details me-1"></i> Detalle de las actividades
                    </a>
                </li>
            </ul>

            {{-- Información de Contexto (Header de la actividad) --}}
            <div class="bg-light p-4 mt-3 rounded-3 mb-4 border-0 shadow-sm">
                <div class="row text-center text-md-start">
                    <div class="col-md-4">
                        <label class="fw-bold text-guinda2 small d-block">Fecha:</label>
                        <span class="text-dark">{{ $actividad->fecha_actividad ? $actividad->fecha_actividad->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold text-guinda2 small d-block">Responsable:</label>
                        <span class="text-dark">{{ mb_strtoupper($actividad->responsable->nombre ?? 'N/A') }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold text-guinda2 small d-block">Sistema:</label>
                        <span class="text-dark">{{ $actividad->sistema->sigla_sistema ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            {{-- Grid de Detalle de Actividades --}}
            <div class="row g-4 mt-2">
                @forelse ($detalleactividades as $detalle)
                    <div class="col-md-6 col-lg-6">
                        <div class="card task-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-guinda border-0 py-4 px-3 text-center">
                                <span class="badge {{ $detalle->estatus == 'Atendida' ? 'bg-success' : 'text-dark' }} rounded-pill px-3 py-1 mb-3" style="{{ $detalle->estatus != 'Atendida' ? 'background-color: #eab64d;' : '' }}">
                                    {{ $detalle->estatus }}
                                </span>
                                <h5 class="fw-bold mb-0 text-white text-uppercase" style="word-wrap: break-word;" title="{{ optional($detalle->tipoRequerimiento)->tipo_requerimiento }}">
                                    {{ optional($detalle->tipoRequerimiento)->tipo_requerimiento ?? 'N/A' }}
                                </h5>
                            </div>
                            <div class="card-body pt-4">
                                <small class="text-muted fw-bold text-uppercase d-block mb-2" style="font-size: 0.75rem;">Descripción:</small>
                                <div class="text-black small mb-0 ">
                                    {!! $detalle->descripcion_actividad !!}
                                </div>
                            </div>
                            <div class="card-footer bg-light border-0 d-flex justify-content-end gap-2 py-3 px-3">
                                <button type="button" class="btn btn-sm btn-outline-secondary border-0 text-guinda btn-editar-detalle" 
                                    data-url="{{ route('detalleactividad.edit', $detalle->id) }}" 
                                    data-action="{{ route('detalleactividad.update', $detalle->id) }}">
                                    <i class="ti ti-pencil fs-4"></i>
                                </button>
                                <form action="{{ route('detalleactividad.destroy', $detalle->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta tarea?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm border-0 text-guinda"><i class="ti ti-trash fs-4"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-light border shadow-sm">
                            <i class="ti ti-clipboard-x fs-1 text-muted d-block mb-2"></i>
                            <span class="text-muted">No hay tareas registradas.</span>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4 d-flex justify-content-end">
                {{ $detalleactividades->appends($request->all())->links() }}
            </div>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="modalDetalleActividad" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-light border-bottom-0 p-4 pb-2">
                <h5 class="modal-title fw-bold text-guinda" id="modalDetalleTitle">Detalle de tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-2">
                <form action="{{ route('detalleactividad.store') }}" method="POST" id="formDetalle" novalidate>
                    @csrf
                    <div id="methodContainer"></div>
                    <input type="hidden" name="actividad_id" value="{{ $actividad->id }}">

                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-bold text-muted small">Tipo de requerimiento: <span class="text-danger">*</span></label>
                            <select name="tipo_requerimiento_id" id="modal_tipo_req" class="form-select border-guinda @error('tipo_requerimiento_id') is-invalid @enderror" required>
                                <option value="">Seleccione...</option>
                                @foreach($tipoRequerimiento as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small d-block">Estatus: <span class="text-danger">*</span></label>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input border-guinda" type="radio" name="estatus" id="modalEstProceso" value="En proceso" checked>
                                <label class="form-check-label small" for="modalEstProceso">En proceso</label>
                            </div>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input border-guinda" type="radio" name="estatus" id="modalEstAtendida" value="Atendida">
                                <label class="form-check-label small" for="modalEstAtendida">Atendida</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Descripción de la actividad: <span class="text-danger">*</span></label>
                        <div class="bg-white border border-guinda rounded">
                            <div id="quillEditor" style="height: 150px;"></div>
                        </div>
                        <input type="hidden" name="descripcion_actividad" id="hidden_descripcion">
                        <div id="error_descripcion" class="text-danger fw-bold small mt-1" style="display: none;">La descripción es obligatoria.</div>
                    </div>

                    <div class="d-flex align-items-center pt-3 mt-2">
                        <button type="submit" class="btn btn-guinda rounded-pill px-4 py-2 shadow-sm fw-bold me-4" style="font-size: 1.1rem;">Guardar</button>
                        <button type="button" class="btn-cancelar text-guinda fw-bold bg-transparent border-0 p-0" data-bs-dismiss="modal" style="font-size: 1.1rem;">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos base */
    .text-guinda { color: #9D2449 !important; }
    .text-guinda2 { color: #4e1c24 !important; }
    .bg-guinda { background-color: #9D2449 !important; }
    .border-guinda { border-color: #9D2449 !important; }
    
    .btn-guinda { background-color: #9D2449; color: white; border: none; transition: 0.3s; }
    .btn-guinda:hover { background-color: #7a1c38; color: white; transform: translateY(-1px); }
    .btn-outline-guinda { color: #9D2449; border-color: #9D2449; background: transparent; }
    .btn-outline-guinda:hover { background-color: #9D2449; color: white; }
    
    .btn-cancelar { 
        transition: all 0.2s; 
        text-decoration: none;
    }
    .btn-cancelar:hover { 
        text-decoration: underline !important; 
        text-decoration-thickness: 2px !important;
        text-underline-offset: 4px;
        opacity: 0.9;
    }

    /* Estilo de Pestañas */
    .border-bottom-guinda { border-bottom: 3px solid #9D2449 !important; }
    .nav-tabs .nav-link { border: none; padding: 0.5rem 1.5rem; color: #6c757d; }
    .nav-tabs .nav-link.active { background: transparent; }

    /* ESTILOS DEL BUSCADOR SEARCHABLE */
    .searchable-dropdown-wrapper { position: relative; width: 100%; }
    .searchable-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #9D2449;
        border-radius: 8px;
        padding: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 2000; 
    }
    .searchable-menu.show { display: block; }
    .searchable-option:hover { background-color: #f8f9fa; color: #9D2449; }

    /* Quill Adjustment */
    .ql-toolbar.ql-snow { border: none !important; border-bottom: 1px solid #9D2449 !important; background: #f8f9fa; }
    .ql-container.ql-snow { border: none !important; }
</style>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    function convertirSelectABuscador(idSelect) {
        const originalSelect = document.getElementById(idSelect);
        if (!originalSelect) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'searchable-dropdown-wrapper';
        const trigger = document.createElement('button');
        trigger.className = 'form-select searchable-trigger border-guinda text-start text-truncate';
        trigger.type = 'button';
        trigger.textContent = originalSelect.options[originalSelect.selectedIndex]?.text || 'Seleccione...';

        const menu = document.createElement('div');
        menu.className = 'searchable-menu';
        const inputSearch = document.createElement('input');
        inputSearch.className = 'form-control mb-2';
        inputSearch.placeholder = 'Buscar...';

        const optionsList = document.createElement('div');
        optionsList.style.maxHeight = '200px';
        optionsList.style.overflowY = 'auto';

        function poblarOpciones() {
            optionsList.innerHTML = '';
            Array.from(originalSelect.options).forEach(option => {
                if(option.value === "") return;
                const item = document.createElement('div');
                item.className = 'searchable-option p-2 rounded cursor-pointer';
                item.textContent = option.text;
                item.addEventListener('click', () => {
                    originalSelect.value = option.value;
                    trigger.textContent = option.text;
                    menu.classList.remove('show');
                    originalSelect.dispatchEvent(new Event('change'));
                });
                optionsList.appendChild(item);
            });
        }

        inputSearch.addEventListener('keyup', (e) => {
            const filtro = e.target.value.toLowerCase();
            optionsList.querySelectorAll('.searchable-option').forEach(item => {
                item.style.display = item.textContent.toLowerCase().includes(filtro) ? 'block' : 'none';
            });
        });

        trigger.addEventListener('click', () => {
            menu.classList.toggle('show');
            if (menu.classList.contains('show')) setTimeout(() => inputSearch.focus(), 100);
        });

        document.addEventListener('click', (e) => { if (!wrapper.contains(e.target)) menu.classList.remove('show'); });

        menu.appendChild(inputSearch);
        menu.appendChild(optionsList);
        wrapper.appendChild(trigger);
        wrapper.appendChild(menu);
        originalSelect.parentNode.insertBefore(wrapper, originalSelect.nextSibling);
        originalSelect.style.display = 'none';
        poblarOpciones();
    }

    document.addEventListener("DOMContentLoaded", function() {
        var quill = new Quill('#quillEditor', {
            theme: 'snow',
            modules: { toolbar: [['bold', 'italic', 'underline'], [{ 'list': 'bullet' }]] }
        });

        // Inicializamos el buscador visual
        convertirSelectABuscador('modal_tipo_req');

        const form = document.getElementById('formDetalle');
        const hiddenDesc = document.getElementById('hidden_descripcion');

        // Validación y envío del form
        form.addEventListener('submit', function(e) {
            var content = quill.root.innerHTML.trim();
            if (content === '<p><br></p>' || content === '') {
                document.getElementById('error_descripcion').style.display = 'block';
                e.preventDefault();
            } else {
                hiddenDesc.value = content;
            }
        });

        // Cargar datos en edición (AQUI SE CORRIGIÓ EL PROBLEMA DE LA DDL VACÍA)
        document.querySelectorAll('.btn-editar-detalle').forEach(btn => {
            btn.addEventListener('click', function() {
                const urlDatos = this.getAttribute('data-url');
                fetch(urlDatos).then(r => r.json()).then(data => {
                    document.getElementById('modalDetalleTitle').textContent = "Editar Tarea";
                    form.action = this.getAttribute('data-action');
                    document.getElementById('methodContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';
                    
                    const sel = document.getElementById('modal_tipo_req');
                    let textoEncontrado = 'Seleccione...';
                    
                    // Buscamos manualmente el valor en las opciones originales para evitar errores de sincronización
                    for (let i = 0; i < sel.options.length; i++) {
                        if (sel.options[i].value == data.tipo_requerimiento_id) {
                            sel.selectedIndex = i;
                            textoEncontrado = sel.options[i].text;
                            break;
                        }
                    }

                    // Actualizamos el botón visual personalizado explícitamente
                    const wrapper = sel.nextElementSibling;
                    if (wrapper && wrapper.classList.contains('searchable-dropdown-wrapper')) {
                        const btnTrigger = wrapper.querySelector('.searchable-trigger');
                        if (btnTrigger) btnTrigger.textContent = textoEncontrado;
                    }

                    // Estatus y Quill
                    if(data.estatus === 'En proceso') document.getElementById('modalEstProceso').checked = true;
                    else document.getElementById('modalEstAtendida').checked = true;

                    quill.root.innerHTML = data.descripcion_actividad;
                    new bootstrap.Modal(document.getElementById('modalDetalleActividad')).show();
                });
            });
        });

        // REINICIO DE MODAL: Para que al dar click en Agregar no salga la info editada anterior
        document.getElementById('modalDetalleActividad').addEventListener('hidden.bs.modal', function () {
            form.action = "{{ route('detalleactividad.store') }}";
            document.getElementById('modalDetalleTitle').textContent = "Detalle de tarea";
            document.getElementById('methodContainer').innerHTML = '';
            
            form.reset();
            quill.root.innerHTML = '';
            
            // Reiniciamos el buscador visual
            const sel = document.getElementById('modal_tipo_req');
            sel.selectedIndex = 0;
            const wrapper = sel.nextElementSibling;
            if (wrapper && wrapper.classList.contains('searchable-dropdown-wrapper')) {
                const btnTrigger = wrapper.querySelector('.searchable-trigger');
                if (btnTrigger) btnTrigger.textContent = 'Seleccione...';
            }
        });
    });
</script>
@endsection