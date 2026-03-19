@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-3">
    
    <div class="card border-0 shadow-sm mb-5 rounded-3">
        
        <div class="card-header bg-light border-bottom-0 pt-4 pb-3 px-4 d-flex justify-content-between align-items-center rounded-top">
            <div>
                <h4 class="fw-bold text-guinda mb-0">Informes de oficios</h4>
            </div>
            <a href="{{ route('informes.oficios') }}" class="btn btn-guinda rounded-pill px-4 shadow-sm fw-bold">
                Limpiar
            </a>
        </div>

        <div class="card-body p-4 bg-white rounded-bottom">
            <form action="{{ route('informes.oficios') }}" method="GET" class="row align-items-end g-3">
                
                <div class="col-md-3">
                    <label class="form-label text-guinda2 fw-bold small">Fecha de recepción del:</label>
                    <input type="date" name="fecha_inicial" class="form-control border-guinda" value="{{ $request->fecha_inicial }}">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label text-guinda2 fw-bold small">al:</label>
                    <input type="date" name="fecha_final" class="form-control border-guinda" value="{{ $request->fecha_final }}">
                </div>
                
                @if(in_array($rol, ['Administrador', 'Administrador TI', 'Admin TI', 'Analista']))
                <div class="col-md-4">
                    <label class="form-label text-guinda2 fw-bold small">Unidad administrativa:</label>
                    <select name="unidad_administrativa_id" id="filtro_dirigido" class="form-select border-guinda">
                        <option value="">Todas las unidades</option>
                        @foreach($unidades as $id => $nombre)
                            <option value="{{ $id }}" {{ $request->unidad_administrativa_id == $id ? 'selected' : '' }}>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @else
                <div class="col-md-4"></div>
                @endif

                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-outline-guinda w-100 rounded-pill shadow-sm fw-bold">
                        Buscar
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div class="row g-4 pb-4">
        
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                    <h5 class="fw-bold text-guinda mb-0">Por tipo de requerimiento</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center p-4">
                    @if(count($seriesPieReq) > 0)
                        <div id="chartPieReq" class="w-100 d-flex justify-content-center"></div>
                    @else
                        <div class="text-muted fst-italic py-5">No hay oficios en este rango de fechas.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                    <h5 class="fw-bold text-guinda mb-1">Requerimientos por Sistema</h5>
                </div>
                <div class="card-body p-4">
                    @if(count($categoriasSis) > 0)
                        <div id="chartSisReq" style="min-height: 380px;"></div>
                    @else
                        <div class="text-muted text-center py-5">No hay datos para mostrar.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                    <h5 class="fw-bold text-guinda mb-0">Por estatus de oficio</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center p-4">
                    @if(count($seriesPieEstatus) > 0)
                        <div id="chartPieEstatus" class="w-100 d-flex justify-content-center"></div>
                    @else
                        <div class="text-muted fst-italic py-5">No hay oficios en este rango de fechas.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                    <h5 class="fw-bold text-guinda mb-1">Requerimientos por Responsable</h5>
                </div>
                <div class="card-body p-4">
                    @if(count($categoriasResp) > 0)
                        <div id="chartRespReq" style="min-height: 380px;"></div>
                    @else
                        <div class="text-muted text-center py-5">No hay responsables asignados en este rango.</div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* PALETA INSTITUCIONAL */
    .bg-guinda { background-color: #9D2449 !important; }
    .border-guinda { border-color: #9D2449 !important; }
    
    /* BOTÓN SÓLIDO (LIMPIAR) */
    .btn-guinda { background-color: #9D2449; color: white; border: none; transition: 0.3s; }
    .btn-guinda:hover { background-color: #7a1c38; color: white; transform: translateY(-1px); }

    /* BOTÓN DELINEADO (BUSCAR) */
    .btn-outline-guinda { color: #9D2449; border: 1px solid #9D2449; background-color: transparent; transition: 0.3s; }
    .btn-outline-guinda:hover { background-color: #9D2449; color: white; }

    /* ESTILOS PARA EL BUSCADOR DDL */
    .searchable-dropdown-wrapper {
        position: relative;
        width: 100%;
    }
    .searchable-trigger {
        background-color: #fff;
        text-align: left;
        padding-right: 2rem;
        position: relative;
    }
    .searchable-trigger::after {
        content: "";
        position: absolute;
        top: 50%;
        right: 1rem;
        transform: translateY(-50%);
        border-top: 0.3em solid;
        border-right: 0.3em solid transparent;
        border-bottom: 0;
        border-left: 0.3em solid transparent;
    }
    .searchable-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background-color: #ffffff;
        border: 1px solid #9D2449;
        border-radius: 0.25rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-top: 4px;
        padding: 8px;
        z-index: 1050;
    }
    .searchable-menu.show {
        display: block;
    }
    .searchable-option {
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    /* ESTILOS DE TARJETAS (HOVER) */
    .custom-hover-wrapper {
        position: relative;
        display: inline-block;
    }
    .custom-hover-card {
        visibility: hidden;
        opacity: 0;
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1050;
        min-width: 220px;
        margin-top: 5px;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }
    .custom-hover-wrapper:hover .custom-hover-card {
        visibility: visible;
        opacity: 1;
    }
    .custom-hover-card::before {
        content: '';
        position: absolute;
        top: -10px;
        left: 0;
        width: 100%;
        height: 10px;
        background: transparent;
    }

    /* OCULTAR DESCARGA CSV EN APEXCHARTS */
    .exportCSV {
        display: none !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // PALETA Y CONFIGURACIÓN APEXCHARTS
    const paletaInstitucional = ['#9D2449', '#977e5b', '#c3b08f', '#8a8a8a', '#c9b088'];

    const apexLocales = [{
        "name": "es",
        "options": {
            "toolbar": {
                "exportToSVG": "Descargar SVG",
                "exportToPNG": "Descargar Imagen",
                "menu": "Descargar",
                "selection": "Selección",
                "selectionZoom": "Acercar Selección",
                "zoomIn": "Acercar",
                "zoomOut": "Alejar",
                "pan": "Navegación",
                "reset": "Reiniciar Zoom"
            }
        }
    }];

    const toolbarConfig = {
        show: true,
        export: {
            csv: { show: false },
            svg: { filename: 'grafica_informe' },
            png: { filename: 'grafica_informe' }
        }
    };

    // 1. PASTEL: Tipo Req
    @if(count($seriesPieReq) > 0)
    var optionsPieReq = {
        series: @json($seriesPieReq),
        labels: @json($labelsPieReq),
        chart: { type: 'pie', width: '100%', height: 350, fontFamily: 'inherit', locales: apexLocales, defaultLocale: 'es', toolbar: toolbarConfig },
        colors: paletaInstitucional,
        legend: { position: 'bottom' },
        dataLabels: { enabled: true, dropShadow: { enabled: false } },
        stroke: { show: true, colors: '#ffffff', width: 2 }
    };
    new ApexCharts(document.querySelector("#chartPieReq"), optionsPieReq).render();
    @endif

    // 2. PASTEL: Estatus
    @if(count($seriesPieEstatus) > 0)
    const estatusColors = @json($labelsPieEstatus).map(estatus => {
        if(estatus === 'CONCLUIDO' || estatus === 'ATENDIDO') return '#8a8a8a';
        if(estatus === 'TURNADO') return '#977e5b';
        if(estatus === 'PENDIENTE') return '#9D2449';
        return '#c3b08f';
    });

    var optionsPieEstatus = {
        series: @json($seriesPieEstatus),
        labels: @json($labelsPieEstatus),
        chart: { type: 'pie', width: '100%', height: 350, fontFamily: 'inherit', locales: apexLocales, defaultLocale: 'es', toolbar: toolbarConfig },
        colors: estatusColors.length > 0 ? estatusColors : paletaInstitucional,
        legend: { position: 'bottom' },
        dataLabels: { enabled: true, dropShadow: { enabled: false } },
        stroke: { show: true, colors: '#ffffff', width: 2 }
    };
    new ApexCharts(document.querySelector("#chartPieEstatus"), optionsPieEstatus).render();
    @endif

    // 3. BARRAS: Sistemas vs Req
    @if(count($categoriasSis) > 0)
    var seriesSisReqData = @json($seriesSisReq);
    var maxSisReq = Math.max(...seriesSisReqData.map(s => Math.max(...s.data)));
    var tickSisReq = (maxSisReq > 0 && maxSisReq < 5) ? maxSisReq : 5;

    var optionsSisReq = {
        series: seriesSisReqData,
        chart: { type: 'bar', height: 400, stacked: true, fontFamily: 'inherit', locales: apexLocales, defaultLocale: 'es', toolbar: toolbarConfig },
        colors: paletaInstitucional,
        plotOptions: { bar: { horizontal: true, borderRadius: 3, barHeight: '55%', dataLabels: { total: { enabled: true, style: { fontWeight: 700 } } } } },
        xaxis: { 
            categories: @json($categoriasSis), 
            title: { text: 'Requerimiento', style: { fontWeight: 600, color: '#9D2449' } }, 
            tickAmount: tickSisReq, // 🚨 ESTO EVITA LOS DECIMALES 🚨
            labels: { style: { colors: '#8a8a8a' }, formatter: function(val){ return parseInt(val); } } 
        },
        yaxis: { title: { text: 'Sistema', style: { fontWeight: 600, color: '#9D2449' } }, labels: { style: { colors: '#8a8a8a', fontSize: '11px', fontWeight: 600 } } },
        legend: { position: 'top', horizontalAlign: 'center', labels: { colors: '#8a8a8a' } },
        fill: { opacity: 1 }, grid: { borderColor: '#f1f1f1' }
    };
    new ApexCharts(document.querySelector("#chartSisReq"), optionsSisReq).render();
    @endif

    // 4. BARRAS: Responsables vs Req
    @if(count($categoriasResp) > 0)
    var seriesRespReqData = @json($seriesRespReq);
    var maxRespReq = Math.max(...seriesRespReqData.map(s => Math.max(...s.data)));
    var tickRespReq = (maxRespReq > 0 && maxRespReq < 5) ? maxRespReq : 5;

    var optionsRespReq = {
        series: seriesRespReqData,
        chart: { type: 'bar', height: 400, stacked: true, fontFamily: 'inherit', locales: apexLocales, defaultLocale: 'es', toolbar: toolbarConfig },
        colors: ['#977e5b', '#c3b08f', '#9D2449', '#c9b088', '#8a8a8a'],
        plotOptions: { bar: { horizontal: true, borderRadius: 3, barHeight: '55%', dataLabels: { total: { enabled: true, style: { fontWeight: 700 } } } } },
        xaxis: { 
            categories: @json($categoriasResp), 
            title: { text: 'Requerimiento', style: { fontWeight: 600, color: '#9D2449' } }, 
            tickAmount: tickRespReq, // 🚨 ESTO EVITA LOS DECIMALES 🚨
            labels: { style: { colors: '#8a8a8a' }, formatter: function(val){ return parseInt(val); } } 
        },
        yaxis: { labels: { style: { colors: '#8a8a8a', fontSize: '11px', fontWeight: 600 } } },
        legend: { position: 'top', horizontalAlign: 'center', labels: { colors: '#8a8a8a' } },
        fill: { opacity: 1 }, grid: { borderColor: '#f1f1f1' }
    };
    new ApexCharts(document.querySelector("#chartRespReq"), optionsRespReq).render();
    @endif

    // SCRIPT DEL BUSCADOR
    function convertirSelectABuscador(idSelect) {
        const originalSelect = document.getElementById(idSelect);
        if (!originalSelect) return;

        const wrapperPrevio = originalSelect.parentNode.querySelector('.searchable-dropdown-wrapper');
        if (wrapperPrevio) wrapperPrevio.remove();

        const wrapper = document.createElement('div');
        wrapper.className = 'searchable-dropdown-wrapper';

        const trigger = document.createElement('button');
        trigger.className = 'form-select searchable-trigger border-guinda text-start text-truncate';
        trigger.type = 'button';

        const selectedOption = originalSelect.options[originalSelect.selectedIndex];
        trigger.textContent = selectedOption ? selectedOption.text : 'Todas las unidades';

        const menu = document.createElement('div');
        menu.className = 'searchable-menu';
        
        const inputSearch = document.createElement('input');
        inputSearch.className = 'form-control mb-2 border-guinda';
        inputSearch.type = 'text';
        inputSearch.placeholder = 'Buscar...';
        inputSearch.onclick = function(e) {
            e.stopPropagation();
        };

        const optionsList = document.createElement('div');
        optionsList.className = 'searchable-options';
        optionsList.style.maxHeight = '200px';
        optionsList.style.overflowY = 'auto';

        function poblarOpciones() {
            optionsList.innerHTML = '';
            Array.from(originalSelect.options).forEach(option => {
                const item = document.createElement('div');
                item.className = 'searchable-option p-2';
                item.style.cursor = 'pointer';
                item.textContent = option.text;

                item.addEventListener('mouseover', () => {
                    item.style.backgroundColor = '#f8f9fa';
                });
                item.addEventListener('mouseout', () => {
                    item.style.backgroundColor = 'transparent';
                });

                item.addEventListener('click', () => {
                    originalSelect.value = option.value;
                    trigger.textContent = option.text;
                    menu.classList.remove('show');
                    inputSearch.value = '';
                    filtrarOpciones('');
                    originalSelect.dispatchEvent(new Event('change'));
                });
                optionsList.appendChild(item);
            });
        }
        poblarOpciones();

        function filtrarOpciones(texto) {
            const items = optionsList.querySelectorAll('.searchable-option');
            const filtro = texto.toLowerCase();
            items.forEach(item => {
                const coincide = item.textContent.toLowerCase().includes(filtro);
                item.style.display = coincide ? 'block' : 'none';
            });
        }

        inputSearch.addEventListener('keyup', (e) => filtrarOpciones(e.target.value));

        trigger.addEventListener('click', (e) => {
            document.querySelectorAll('.searchable-menu').forEach(m => {
                if (m !== menu) m.classList.remove('show');
            });
            menu.classList.toggle('show');
            if (menu.classList.contains('show')) setTimeout(() => inputSearch.focus(), 100);
        });

        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) menu.classList.remove('show');
        });

        menu.appendChild(inputSearch);
        menu.appendChild(optionsList);
        wrapper.appendChild(trigger);
        wrapper.appendChild(menu);

        originalSelect.parentNode.insertBefore(wrapper, originalSelect.nextSibling);
        originalSelect.style.display = 'none';
    }

    // Inicializar el buscador
    convertirSelectABuscador('filtro_dirigido');

});
</script>
@endsection